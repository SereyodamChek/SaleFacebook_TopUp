<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class OrderItemController extends Controller
{
    /**
     * List items for a specific order (admin)
     */
    public function index(Order $order)
    {
        $order->load(['items.product', 'user']);

        $items = $order->items;

        $total = $items->sum(fn ($i) => (float)$i->price * (int)$i->qty);

        return view('admin.orders.items', compact('order', 'items', 'total'));
    }

    /**
     * Show a single order item (optional)
     */
    public function show(OrderItem $orderItem)
    {
        $orderItem->load(['order.user', 'product']);

        return view('admin.orders.item-show', compact('orderItem'));
    }
}
