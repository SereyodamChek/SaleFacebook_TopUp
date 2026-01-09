<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuItemController extends Controller
{
    public function index(MenuCategory $category)
    {
        $items = MenuItem::where('menu_category_id', $category->id)
            ->orderBy('sort')
            ->get();

        return view('admin.menu.items.index', compact('category', 'items'));
    }

    public function store(Request $request, MenuCategory $category)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'url'         => 'nullable|string|max:2048',
            'icon'        => 'nullable|image|max:2048', // 2MB
            'status'      => 'nullable|string|max:255',
            'status_type' => 'nullable|string|max:50',
            'sort'        => 'nullable|integer|min:0',
            'is_active'   => 'nullable|boolean',
        ]);

        $iconPath = null;
        if ($request->hasFile('icon')) {
            // stores in storage/app/public/menu-icons/...
            $iconPath = $request->file('icon')->store('menu-icons', 'public');
        }

        MenuItem::create([
            'menu_category_id' => $category->id,
            'title'       => $validated['title'],
            'url'         => $validated['url'] ?? null,
            'icon'        => $iconPath,
            'status'      => $validated['status'] ?? null,
            'status_type' => $validated['status_type'] ?? null,
            'sort'        => $validated['sort'] ?? 0,
            'is_active'   => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('admin.menu.categories.items.index', $category->id)
            ->with('success', 'Item created successfully.');
    }

    public function update(Request $request, MenuCategory $category, MenuItem $item)
    {
        if ((int)$item->menu_category_id !== (int)$category->id) {
            return back()->with('error', 'Invalid item for this category.');
        }

        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'url'         => 'nullable|string|max:2048',
            'icon'        => 'nullable|image|max:2048',
            'status'      => 'nullable|string|max:255',
            'status_type' => 'nullable|string|max:50',
            'sort'        => 'nullable|integer|min:0',
            'is_active'   => 'nullable|boolean',
        ]);

        // If a new icon uploaded, replace old file
        if ($request->hasFile('icon')) {
            if ($item->icon) {
                Storage::disk('public')->delete($item->icon);
            }
            $item->icon = $request->file('icon')->store('menu-icons', 'public');
        }

        $item->update([
            'title'       => $validated['title'],
            'url'         => $validated['url'] ?? null,
            'status'      => $validated['status'] ?? null,
            'status_type' => $validated['status_type'] ?? null,
            'sort'        => $validated['sort'] ?? 0,
            'is_active'   => $request->boolean('is_active'),
            // icon handled above
        ]);

        return redirect()
            ->route('admin.menu.categories.items.index', $category->id)
            ->with('success', 'Item updated successfully.');
    }

    public function destroy(MenuCategory $category, MenuItem $item)
    {
        if ((int)$item->menu_category_id !== (int)$category->id) {
            return back()->with('error', 'Invalid item for this category.');
        }

        // delete icon file
        if ($item->icon) {
            Storage::disk('public')->delete($item->icon);
        }

        $item->delete();

        return redirect()
            ->route('admin.menu.categories.items.index', $category->id)
            ->with('success', 'Item deleted successfully.');
    }
}
