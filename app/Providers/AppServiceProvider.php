<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\MenuCategory;
use Illuminate\Support\Facades\View;
use App\Models\Cart;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
   public function boot(): void
{
    View::composer('*', function ($view) {

        // ✅ Mega menu (your existing code)
        $menu = MenuCategory::query()
            ->where('is_active', true)
            ->with(['items' => function ($q) {
                $q->where('is_active', true)->orderBy('sort');
            }])
            ->orderBy('sort')
            ->get()
            ->groupBy('group_key'); // product, recharge, association

        $view->with('megaMenu', $menu);


        if (auth()->check()) {
    $u = auth()->user();

    // load wallet
    $u->loadMissing('wallet');

    // auto-create wallet if missing
    if (!$u->wallet) {
        $u->wallet()->create([
            'balance' => 0,
            'total_deposit' => 0,
            'used_balance' => 0,
            'discount_percent' => 0,
        ]);
        $u->load('wallet');
    }
}




$categories = MenuCategory::with('items')->get();
        $view->with('categories', $categories);


        // ✅ Cart badge count (DB)
        $cartCount = 0;
        $cartQty = 0;

        if (auth()->check()) {
            $cart = \App\Models\Cart::query()
                ->where('user_id', auth()->id())
                ->withCount('items') // counts rows in cart_items
                ->with('items:id,cart_id,qty') // for qty sum
                ->first();

            $cartCount = $cart?->items_count ?? 0;
            $cartQty   = $cart ? $cart->items->sum('qty') : 0;
        }

        // Choose what you want to use in Blade:
        // $cartCount = number of cart rows
        // $cartQty   = total quantity
        $view->with('cartCount', $cartCount);
        $view->with('cartQty', $cartQty);
    });
}

}
