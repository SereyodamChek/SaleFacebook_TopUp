<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\MenuCategoryController;
use App\Http\Controllers\Admin\MenuItemController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\OrderItemController;

use App\Http\Controllers\Front\StoreController;
use App\Http\Controllers\Front\TopupController;
use App\Http\Controllers\Front\CartController;

use App\Http\Controllers\WalletController;
use App\Http\Controllers\Customer\ProfileController;
use App\Http\Controllers\Customer\ActivityLogController;
use App\Http\Controllers\Customer\OrderController;

/*
|--------------------------------------------------------------------------
| Public routes
|--------------------------------------------------------------------------
*/
Route::get('/', [StoreController::class, 'home'])->name('home');

/*
|--------------------------------------------------------------------------
| Auth routes
|--------------------------------------------------------------------------
*/
Auth::routes();

/*
|--------------------------------------------------------------------------
| PUBLIC TOPUP VERIFY (IMPORTANT)
|--------------------------------------------------------------------------
| ⚠️ Verify MUST be public (no auth)
| Reason: payment is async, session may expire
*/
Route::get('/topup/{id}/verify', [TopupController::class, 'verify'])
    ->name('topup.verify');

/*
|--------------------------------------------------------------------------
| Customer / Store routes (AUTH REQUIRED)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // Customer Dashboard
    Route::get('/customer/dashboard', function () {
        return view('customer.profile.edit');
    })->name('customer.dashboard');

    Route::get('/dashboard', function () {
        return redirect()->route('customer.dashboard');
    })->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('customer.profile.edit');

    Route::put('/profile', [ProfileController::class, 'update'])
        ->name('customer.profile.update');

    // Activity log
    Route::get('/activity', [ActivityLogController::class, 'index'])
        ->name('customer.activity.index');

    // Orders
    Route::get('/orders', [OrderController::class, 'index'])
        ->name('customer.orders.index');

    Route::get('/orders/{order}', [OrderController::class, 'show'])
        ->name('customer.orders.show');

    Route::get('/orders/{order}/invoice', [OrderController::class, 'invoice'])
        ->name('customer.orders.invoice');

    // Cart
    Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
    Route::post('/cart/remove/{product}', [CartController::class, 'remove'])->name('cart.remove');

    // Checkout
    Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout.index');
    Route::post('/checkout/pay', [CartController::class, 'pay'])->name('checkout.pay');

    // =======================
    // TOPUP (AUTH REQUIRED)
    // =======================

    Route::get('/topup', [TopupController::class, 'create'])
        ->name('topup.create');

    // Hosting-safe GET
    Route::get('/topup/pay', [TopupController::class, 'store'])
        ->name('topup.store');

    // Show QR (AUTH)
    Route::get('/topup/{id}', [TopupController::class, 'show'])
        ->name('topup.show');

    // Store
    Route::get('/store', [StoreController::class, 'index'])->name('store.index');
    Route::get('/store/{product}', [StoreController::class, 'show'])->name('store.show');
    Route::get('/store/product/{product}', [StoreController::class, 'show'])
        ->name('store.product.show');
});

/*
|--------------------------------------------------------------------------
| Admin routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/', function () {
            return view('admin.dashboard');
        })->name('dashboard');

        // Users
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');

        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::patch('/users/{user}/password', [UserController::class, 'updatePassword'])->name('users.password');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

        // Products
        Route::resource('products', ProductController::class)
            ->only(['index', 'store', 'update', 'destroy']);

        // Wallets
        Route::resource('wallets', WalletController::class);

        // Orders (admin)
        Route::get('/orders/{order}/items', [OrderItemController::class, 'index'])
            ->name('orders.items.index');

        Route::get('/order-items/{orderItem}', [OrderItemController::class, 'show'])
            ->name('order-items.show');

        // Menu
        Route::prefix('menu')->name('menu.')->group(function () {
            Route::resource('categories', MenuCategoryController::class)
                ->only(['index', 'store', 'update', 'destroy']);

            Route::resource('categories.items', MenuItemController::class)
                ->only(['index', 'store', 'update', 'destroy']);
        });
    });
