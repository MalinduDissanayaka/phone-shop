<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with('children')->whereNull('parent_id')->orderBy('name')->get();

        return view('inventory.categories.index', compact('categories'));
    }

    public function create()
    {
        $mainCategories = Category::whereNull('parent_id')->orderBy('name')->get();

        return view('inventory.categories.create', compact('mainCategories'));
    }

    public function store(Request $request)
    {
        $validated = $this->validated($request);

        Category::create($validated);

        return redirect()->route('inventory.categories.index')->with('status', 'Category created.');
    }

    public function edit(Category $category)
    {
        $mainCategories = Category::whereNull('parent_id')->where('id', '!=', $category->id)->orderBy('name')->get();

        return view('inventory.categories.edit', compact('category', 'mainCategories'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $this->validated($request, $category);

        $category->update($validated);

        return redirect()->route('inventory.categories.index')->with('status', 'Category updated.');
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()->route('inventory.categories.index')->with('status', 'Category deleted.');
    }

    private function validated(Request $request, ?Category $category = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'parent_id' => [
                'nullable',
                Rule::exists('categories', 'id'),
                $category ? Rule::notIn([$category->id]) : 'nullable',
            ],
        ], [], ['parent_id' => 'main category']);
    }
}
