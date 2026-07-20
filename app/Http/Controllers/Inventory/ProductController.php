<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Phone;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function create()
    {
        $mainCategories = Category::with('children')->whereNull('parent_id')->orderBy('name')->get();
        $products = Phone::with('category.parent')->latest()->get();

        return view('inventory.products.create', compact('mainCategories', 'products'));
    }

    public function store(Request $request)
    {
        $validated = $this->validated($request);
        $categoryId = $this->resolveCategoryId($validated);

        $image = $request->file('image');
        $imageName = time() . '.' . $image->extension();
        $image->move(public_path('images'), $imageName);

        Phone::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'price' => $validated['price'],
            'image' => $imageName,
            'category_id' => $categoryId,
        ]);

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
        $product->price = $validated['price'];
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
            'price' => ['required', 'numeric', 'min:0'],
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
