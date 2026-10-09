<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Enums\StockMovementType;
use App\Models\Category;
use App\Models\Phone;
use App\Services\StockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function create()
    {
        $mainCategories = Category::with('children')->whereNull('parent_id')->orderBy('name')->get();
        $products = Phone::with('category.parent')->latest()->get();

        return view('inventory.products.create', compact('mainCategories', 'products'));
    }

    public function store(Request $request, StockService $stock)
    {
        $validated = $this->validated($request);
        $categoryId = $this->resolveCategoryId($validated);

        $image = $request->file('image');
        $imageName = time() . '.' . $image->extension();
        $image->move(public_path('images'), $imageName);

        DB::transaction(function () use ($validated, $imageName, $categoryId, $stock, $request) {
            $product = Phone::create([
                'name' => $validated['name'],
                'description' => $validated['description'],
                'cost_price' => $validated['cost_price'],
                'price' => $validated['price'],
                'reorder_level' => $validated['reorder_level'],
                'image' => $imageName,
                'category_id' => $categoryId,
            ]);

            if (($validated['opening_stock'] ?? 0) > 0) {
                $stock->adjust($product, StockMovementType::Opening, (int) $validated['opening_stock'], $request->user());
            }
        });

        return redirect()->route('inventory.products.create')->with('status', 'Product added.');
    }

    public function edit(Phone $product)
    {
        $mainCategories = Category::with('children')->whereNull('parent_id')->orderBy('name')->get();

        return view('inventory.products.edit', ['product' => $product, 'mainCategories' => $mainCategories]);
    }

    public function update(Request $request, Phone $product)
    {
        $validated = $this->validated($request, forUpdate: true);
        $categoryId = $this->resolveCategoryId($validated);

        $product->name = $validated['name'];
        $product->description = $validated['description'];
        $product->cost_price = $validated['cost_price'];
        $product->price = $validated['price'];
        $product->reorder_level = $validated['reorder_level'];
        $product->category_id = $categoryId;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->extension();
            $image->move(public_path('images'), $imageName);
            $product->image = $imageName;
        }

        $product->save();

        return redirect()->route('inventory.products.create')->with('status', 'Product updated.');
    }

    public function destroy(Phone $product)
    {
        $product->delete();

        return redirect()->route('inventory.products.create')->with('status', 'Product deleted.');
    }

    private function validated(Request $request, bool $forUpdate = false): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'cost_price' => ['required', 'numeric', 'min:0'],
            'price' => ['required', 'numeric', 'min:0'],
            'reorder_level' => ['required', 'integer', 'min:0', 'max:1000000'],
            'opening_stock' => [$forUpdate ? 'prohibited' : 'nullable', 'integer', 'min:0', 'max:1000000'],
            'image' => [$forUpdate ? 'nullable' : 'required', 'image', 'max:4096'],
            'main_category_id' => ['nullable', 'exists:categories,id'],
            'category_id' => ['nullable', 'exists:categories,id'],
        ]);
    }

    private function resolveCategoryId(array $validated): ?int
    {
        return $validated['category_id'] ?? $validated['main_category_id'] ?? null;
    }
}
