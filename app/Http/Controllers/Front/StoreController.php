<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\MenuCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StoreController extends Controller
{



public function home(Request $request)
{
    $selectedGroup = $request->query('group', 'product');
    $selectedItemId = $request->query('item');

    $categories = MenuCategory::with(['items' => function ($q) {
            $q->where('is_active', true)->orderBy('sort');
        }])
        ->where('is_active', true)
        ->where('group_key', $selectedGroup)
        ->orderBy('sort')
        ->get();

    $products = Product::query()
        ->where('is_active', true)
        ->when($selectedItemId, fn($q) => $q->where('menu_item_id', $selectedItemId))
        ->orderByDesc('id')
        ->paginate(12)
        ->withQueryString();

    // mega menu
    $megaMenu = MenuCategory::with(['items' => function ($q) {
            $q->where('is_active', true)->orderBy('sort');
        }])
        ->where('is_active', true)
        ->orderBy('sort')
        ->get()
        ->groupBy('group_key');

    $user = Auth::user();

    // If your site requires login, add middleware auth. Otherwise guard:
    $balance = ['current'=>0,'deposit'=>0,'used'=>0,'discount'=>0];

    if ($user) {
        $user->load('wallet');

        if (!$user->wallet) {
            $user->wallet()->create([
                'balance' => 0,
                'total_deposit' => 0,
                'used_balance' => 0,
                'discount_percent' => 0,
            ]);
            $user->load('wallet');
        }

        $balance = [
            'current'  => (float) $user->wallet->balance,
            'deposit'  => (float) $user->wallet->total_deposit,
            'used'     => (float) $user->wallet->used_balance,
            'discount' => (int) $user->wallet->discount_percent,
        ];
    }


    // Get all menu items from categories
$allItems = $categories->flatMap(fn($c) => $c->items ?? collect());

// Preload products grouped by menu_item_id (only active)
$itemIds = $allItems->pluck('id')->filter()->values();

$productsByItem = Product::query()
    ->where('is_active', true)
    ->whereIn('menu_item_id', $itemIds)
    ->orderByDesc('id')
    ->get()
    ->groupBy('menu_item_id');


    $selectedCategoryId = null; // not used here, keep compatibility

  return view('front.store.index_bars', compact(
    'products',
    'megaMenu',
    'categories',
    'selectedGroup',
    'selectedCategoryId',
    'selectedItemId',
    'balance',
    'user',
    'productsByItem'
));

}



    public function index(Request $request)
    {
        $selectedGroup       = $request->query('group', 'product');
        $selectedCategoryId  = $request->query('cat');
        $selectedItemId      = $request->query('item');

        $categories = MenuCategory::with(['items' => function ($q) {
                $q->where('is_active', true)->orderBy('sort');
            }])
            ->where('is_active', true)
            ->where('group_key', $selectedGroup)
            ->orderBy('sort')
            ->get();

        $products = Product::query()
            ->where('is_active', true)
            ->when($selectedItemId, fn ($q) =>
                $q->where('menu_item_id', $selectedItemId)
            )
            ->when($selectedCategoryId && !$selectedItemId, function ($q) use ($categories, $selectedCategoryId) {
                $itemIds = $categories
                    ->firstWhere('id', (int)$selectedCategoryId)
                    ?->items
                    ?->pluck('id');

                if ($itemIds?->count()) {
                    $q->whereIn('menu_item_id', $itemIds);
                }
            })
            ->orderByDesc('id')
            ->paginate(12)
            ->withQueryString();

        $megaMenu = MenuCategory::with(['items' => function ($q) {
                $q->where('is_active', true)->orderBy('sort');
            }])
            ->where('is_active', true)
            ->orderBy('sort')
            ->get()
            ->groupBy('group_key');

        $user = Auth::user();

// Load wallet
$user->load('wallet');

// Auto-create wallet if missing
if (!$user->wallet) {
    $user->wallet()->create([
        'balance' => 0,
        'total_deposit' => 0,
        'used_balance' => 0,
        'discount_percent' => 0,
    ]);
    $user->load('wallet');
}

$balance = [
    'current'  => (float) $user->wallet->balance,
    'deposit'  => (float) $user->wallet->total_deposit,
    'used'     => (float) $user->wallet->used_balance,
    'discount' => (int) $user->wallet->discount_percent,
];


        return view('front.store.index', compact(
            'products',
            'megaMenu',
            'categories',
            'selectedGroup',
            'selectedCategoryId',
            'selectedItemId',
            'balance',
            'user'
        ));

        


    }

    // ✅ PRODUCT DETAIL PAGE
public function show(Product $product)
{
    abort_if(!$product->is_active, 404);

    $megaMenu = MenuCategory::with(['items' => function ($q) {
            $q->where('is_active', true)->orderBy('sort');
        }])
        ->where('is_active', true)
        ->orderBy('sort')
        ->get()
        ->groupBy('group_key');

    $user = Auth::user();

    // ✅ Load wallet relationship
    $user->load('wallet');

    // ✅ Auto-create wallet if missing
    if (!$user->wallet) {
        $user->wallet()->create([
            'balance' => 0,
            'total_deposit' => 0,
            'used_balance' => 0,
            'discount_percent' => 0,
        ]);

        $user->load('wallet');
    }

    // ✅ Real balance from DB
    $balance = [
        'current'  => (float) $user->wallet->balance,
        'deposit'  => (float) $user->wallet->total_deposit,
        'used'     => (float) $user->wallet->used_balance,
        'discount' => (int) $user->wallet->discount_percent,
    ];

    return view('front.store.show', compact('product', 'megaMenu', 'user', 'balance'));
}


}
