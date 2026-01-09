<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class CartController extends Controller
{
    private function getOrCreateCart(int $userId): Cart
    {
        return Cart::firstOrCreate(['user_id' => $userId]);
    }

    public function index(Request $request)
    {
        $cart = $this->getOrCreateCart($request->user()->id);

        $items = $cart->items()
            ->with('product')
            ->orderByDesc('id')
            ->get();

        $total = $items->sum(fn ($i) => $i->price * $i->qty);

        return view('front.cart.index', compact('cart', 'items', 'total'));
    }

    public function add(Request $request, Product $product)
    {
        abort_if(!$product->is_active, 404);
        abort_if((int)$product->stock <= 0, 422);

        $qty = max(1, (int)$request->input('qty', 1));
        $qty = min($qty, (int)$product->stock);

        $cart = $this->getOrCreateCart($request->user()->id);

        DB::transaction(function () use ($cart, $product, $qty) {
            $item = CartItem::where('cart_id', $cart->id)
                ->where('product_id', $product->id)
                ->lockForUpdate()
                ->first();

            if ($item) {
                $newQty = min($item->qty + $qty, (int)$product->stock);
                $item->update([
                    'qty' => $newQty,
                    // keep price snapshot (or update price if you want)
                ]);
            } else {
                CartItem::create([
                    'cart_id' => $cart->id,
                    'product_id' => $product->id,
                    'qty' => $qty,
                    'price' => (float)$product->price, // snapshot
                ]);
            }
        });

       if ($request->input('redirect') === 'checkout') {
    return redirect()->route('checkout.index');
}

return back()->with('success', 'Added to cart.');

    }

    public function update(Request $request)
    {
        $cart = $this->getOrCreateCart($request->user()->id);
        $itemsInput = $request->input('items', []);

        DB::transaction(function () use ($cart, $itemsInput) {
            foreach ($itemsInput as $productId => $qty) {
                $qty = (int)$qty;

                $item = CartItem::where('cart_id', $cart->id)
                    ->where('product_id', (int)$productId)
                    ->with('product')
                    ->lockForUpdate()
                    ->first();

                if (!$item) continue;

                if ($qty <= 0) {
                    $item->delete();
                    continue;
                }

                $maxStock = (int)($item->product->stock ?? 0);
                $qty = min($qty, max(1, $maxStock));

                $item->update(['qty' => $qty]);
            }
        });

        return back()->with('success', 'Cart updated.');
    }

    public function remove(Request $request, $product)
{
    $cart = $this->getOrCreateCart($request->user()->id);

    \App\Models\CartItem::where('cart_id', $cart->id)
        ->where('product_id', (int)$product)
        ->delete();

    return redirect()->route('cart.index')->with('success', 'Item removed.');
}


    public function checkout(Request $request)
{
    $user = $request->user();

    $cart = $this->getOrCreateCart($user->id);

    $items = $cart->items()->with('product')->get();
    if ($items->isEmpty()) {
        return redirect()->route('cart.index')->with('success', 'Your cart is empty.');
    }

    $total = $items->sum(fn ($i) => $i->price * $i->qty);

    // ✅ Wallet check
    $user->load('wallet');

    if (!$user->wallet || (float)$user->wallet->balance < (float)$total) {
        return redirect()
            ->route('topup.create')
            ->with('error', 'Insufficient wallet balance. Please top up before checkout.');
    }

    return view('front.cart.checkout', compact('cart', 'items', 'total'));
}



public function pay(Request $request)
{
    $user = $request->user();
    $cart = $this->getOrCreateCart($user->id);

    try {
        DB::transaction(function () use ($user, $cart) {

            $wallet = Wallet::where('user_id', $user->id)->lockForUpdate()->first();

            if (!$wallet) {
                $wallet = Wallet::create([
                    'user_id' => $user->id,
                    'balance' => 0,
                    'total_deposit' => 0,
                    'used_balance' => 0,
                    'discount_percent' => 0,
                ]);
            }

            $items = $cart->items()->with('product')->lockForUpdate()->get();

            if ($items->isEmpty()) {
                throw new \RuntimeException('CART_EMPTY');
            }

            $total = 0;

            foreach ($items as $item) {
                $product = $item->product;

                if (!$product || !$product->is_active) {
                    throw new \RuntimeException('PRODUCT_NOT_AVAILABLE');
                }

                if ((int)$product->stock < (int)$item->qty) {
                    throw new \RuntimeException('OUT_OF_STOCK');
                }

                $total += (float)$item->price * (int)$item->qty;
            }

            if ((float)$wallet->balance < (float)$total) {
                throw new \RuntimeException('INSUFFICIENT_BALANCE');
            }

            // ✅ CREATE ORDER
            $order = Order::create([
                'user_id' => $user->id,
                'total_amount' => $total,
                'paid_amount' => $total,
                'status' => 'paid',
                'reference' => 'WALLET-' . now()->format('YmdHis'),
            ]);

            // ✅ CREATE ORDER ITEMS
            foreach ($items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'product_title' => $item->product->title,
                    'price' => (float)$item->price,
                    'qty' => (int)$item->qty,
                ]);
            }

            // ✅ WALLET DEDUCT
            $wallet->balance = (float)$wallet->balance - (float)$total;
            $wallet->used_balance = (float)$wallet->used_balance + (float)$total;
            $wallet->save();

            // ✅ STOCK UPDATE
            foreach ($items as $item) {
                $product = $item->product;
                $qty = (int)$item->qty;

                $product->stock = (int)$product->stock - $qty;

                if (isset($product->sold_out_amount)) {
                    $product->sold_out_amount = (int)$product->sold_out_amount + $qty;
                }

                $product->save();
            }

            // ✅ CLEAR CART
            $cart->items()->delete();
        });

        return redirect()->route('store.index')->with('success', 'Payment successful. Order saved.');
    } catch (\RuntimeException $e) {

        if ($e->getMessage() === 'INSUFFICIENT_BALANCE') {
            return redirect()->route('topup.create')->with('error', 'Insufficient wallet balance. Please top up.');
        }

        if ($e->getMessage() === 'OUT_OF_STOCK') {
            return redirect()->route('cart.index')->with('error', 'Some items are out of stock.');
        }

        return redirect()->route('cart.index')->with('error', 'Checkout failed.');
    }
}

}
