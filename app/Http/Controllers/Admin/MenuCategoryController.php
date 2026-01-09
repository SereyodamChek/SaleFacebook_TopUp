<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuCategory;
use Illuminate\Http\Request;

class MenuCategoryController extends Controller
{
    public function index()
    {
        $categories = MenuCategory::orderBy('group_key')
            ->orderBy('sort')
            ->get();

        return view('admin.menu.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'group_key' => 'required|string|max:255',
            'title'     => 'required|string|max:255',
            'sort'      => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        MenuCategory::create([
            'group_key' => $validated['group_key'],
            'title'     => $validated['title'],
            'sort'      => $validated['sort'] ?? 0,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('admin.menu.categories.index')
            ->with('success', 'Category created successfully.');
    }

    // IMPORTANT: match route param {category}
    public function update(Request $request, MenuCategory $category)
    {
        $validated = $request->validate([
            'group_key' => 'required|string|max:255',
            'title'     => 'required|string|max:255',
            'sort'      => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $category->update([
            'group_key' => $validated['group_key'],
            'title'     => $validated['title'],
            'sort'      => $validated['sort'] ?? 0,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('admin.menu.categories.index')
            ->with('success', 'Category updated successfully.');
    }

    // IMPORTANT: match route param {category}
    public function destroy(MenuCategory $category)
    {
        // Optional (only if you don't have FK cascade):
        // $category->items()->delete();

        $category->delete();

        return redirect()
            ->route('admin.menu.categories.index')
            ->with('success', 'Category deleted successfully.');
    }
}
