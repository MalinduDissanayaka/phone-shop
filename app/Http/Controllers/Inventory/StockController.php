<?php

namespace App\Http\Controllers\Inventory;

use App\Enums\StockMovementType;
use App\Enums\StockStatus;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Phone;
use App\Services\StockService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class StockController extends Controller
{
    private const HISTORY_LIMIT = 5;

    public function index(Request $request)
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'category' => ['nullable', 'integer'],
            'status' => ['nullable', Rule::enum(StockStatus::class)],
            'sort' => ['nullable', Rule::in(['name', 'stock_asc', 'stock_desc'])],
        ]);

        $status = isset($filters['status']) ? StockStatus::from($filters['status']) : null;

        $products = Phone::query()
            ->with([
                'category.parent',
                'stockMovements' => fn ($q) => $q->with('user:id,name')->latest()->latest('id')->limit(self::HISTORY_LIMIT),
            ])
            ->when($filters['q'] ?? null, fn ($q, $term) => $q->where('name', 'like', '%' . $term . '%'))
            ->when($filters['category'] ?? null, function ($q, $categoryId) {
                // A main category also matches products filed under its subcategories.
                $q->whereIn('category_id', Category::where('id', $categoryId)->orWhere('parent_id', $categoryId)->select('id'));
            })
            ->when($status, fn ($q, $status) => $q->withStockStatus($status))
            ->when($filters['sort'] ?? 'name', fn ($q, $sort) => match ($sort) {
                'stock_asc' => $q->orderBy('stock_quantity')->orderBy('name'),
                'stock_desc' => $q->orderByDesc('stock_quantity')->orderBy('name'),
                default => $q->orderBy('name'),
            })
            ->paginate(24)
            ->withQueryString();

        return view('inventory.stock.index', [
            'products' => $products,
            'summary' => $this->summary(),
            'mainCategories' => Category::with('children')->whereNull('parent_id')->orderBy('name')->get(),
            'filters' => $filters,
            'movementTypes' => StockMovementType::manual(),
            'historyLimit' => self::HISTORY_LIMIT,
        ]);
    }

    public function adjust(Request $request, Phone $product, StockService $stock)
    {
        $validated = $request->validateWithBag('stockAdjustment', [
            'type' => ['required', Rule::enum(StockMovementType::class)->only(StockMovementType::manual())],
            'quantity' => ['required', 'integer', 'min:0', 'max:1000000'],
            'note' => ['nullable', 'string', 'max:255'],
        ]);

        $type = StockMovementType::from($validated['type']);

        if ($type !== StockMovementType::Adjustment && $validated['quantity'] < 1) {
            return back()->withInput()->withErrors(['quantity' => 'Enter at least 1 unit.'], 'stockAdjustment');
        }

        try {
            $stock->adjust($product, $type, $validated['quantity'], $request->user(), $validated['note'] ?? null);
        } catch (ValidationException $e) {
            return back()->withInput()->withErrors($e->errors(), 'stockAdjustment');
        }

        return back()->with('status', "{$type->label()} recorded for {$product->name}. New balance: {$product->stock_quantity}.");
    }

    /**
     * Inventory-wide totals (unaffected by the current filters).
     */
    private function summary(): array
    {
        $totals = Phone::query()
            ->selectRaw('COUNT(*) as products')
            ->selectRaw('COALESCE(SUM(stock_quantity), 0) as units')
            ->selectRaw('COALESCE(SUM(stock_quantity * COALESCE(cost_price, 0)), 0) as cost_value')
            ->selectRaw('COALESCE(SUM(stock_quantity * price), 0) as retail_value')
            ->first();

        return [
            'products' => (int) $totals->products,
            'units' => (int) $totals->units,
            'cost_value' => (float) $totals->cost_value,
            'retail_value' => (float) $totals->retail_value,
            'low' => Phone::withStockStatus(StockStatus::Low)->count(),
            'out' => Phone::withStockStatus(StockStatus::Out)->count(),
        ];
    }
}
