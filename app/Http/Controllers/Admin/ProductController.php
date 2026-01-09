<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Product;
use App\Models\MenuItem;
use Illuminate\Http\Request;

class ProductController extends Controller
{
  public function index()
  {
    $products = Product::with('menuItem.category')
      ->orderByDesc('id')
      ->get();

    // Optional dropdown to connect product to mega menu item
    $menuItems = MenuItem::with('category')
      ->orderBy('menu_category_id')
      ->orderBy('sort')
      ->get();

    return view('admin.products.index', compact('products', 'menuItems'));
  }

  public function store(Request $request)
  {
    $validated = $request->validate([
      'menu_item_id'      => 'nullable|exists:menu_items,id',
      'title'             => 'required|string|max:255',
      'price'             => 'required|numeric|min:0',
      'stock'             => 'required|integer|min:0',
      'sold_out_amount'   => 'nullable|integer|min:0',
      'description'       => 'nullable|string',
      'is_active'         => 'nullable|boolean',
    ]);

    Product::create([
      'menu_item_id'    => $validated['menu_item_id'] ?? null,
      'title'           => $validated['title'],
      'price'           => $validated['price'],
      'stock'           => $validated['stock'],
      'sold_out_amount' => $validated['sold_out_amount'] ?? 0,
      'description'     => $validated['description'] ?? null,
      'is_active'       => $request->boolean('is_active'),
    ]);

    return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
  }

  public function update(Request $request, Product $product)
  {
    $validated = $request->validate([
      'menu_item_id'      => 'nullable|exists:menu_items,id',
      'title'             => 'required|string|max:255',
      'price'             => 'required|numeric|min:0',
      'stock'             => 'required|integer|min:0',
      'sold_out_amount'   => 'nullable|integer|min:0',
      'description'       => 'nullable|string',
      'is_active'         => 'nullable|boolean',
    ]);

    $product->update([
      'menu_item_id'    => $validated['menu_item_id'] ?? null,
      'title'           => $validated['title'],
      'price'           => $validated['price'],
      'stock'           => $validated['stock'],
      'sold_out_amount' => $validated['sold_out_amount'] ?? 0,
      'description'     => $validated['description'] ?? null,
      'is_active'       => $request->boolean('is_active'),
    ]);

    return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
  }

  public function destroy(Product $product)
  {
    $product->delete();

    return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
  }
}
