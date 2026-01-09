<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;


class OrderController extends Controller
{
    /**
     * List orders for logged in customer
     */
  public function index(Request $request)
{
    $orders = Order::query()
        ->where('user_id', $request->user()->id)
        ->withCount('items')
        ->orderByDesc('id')
        ->paginate(10);

    return view('customer.orders.index', compact('orders'));
}

    

    /**
     * Show single order detail (only owner's order)
     */
  public function show(Request $request, $order)
{
    $order = Order::query()
        ->where('id', $order)
        ->where('user_id', $request->user()->id)
        ->with(['items.product'])
        ->firstOrFail();

    $subtotal = $order->items->sum(fn ($i) => (float)$i->price * (int)$i->qty);

    return view('customer.orders.show', compact('order', 'subtotal'));
}

    
public function invoice(Request $request, $order)
{
    \Log::info('INVOICE HIT', [
        'user_id' => $request->user()?->id,
        'order_param' => $order,
        'ip' => $request->ip(),
        'url' => $request->fullUrl(),
    ]);

    $order = Order::query()
        ->where('id', $order)
        ->where('user_id', $request->user()->id)
        ->with(['user','items.product'])
        ->firstOrFail();

    $subtotal = $order->items->sum(fn ($i) => (float)$i->price * (int)$i->qty);
    $total = (float) $order->total_amount;

    try {
        $pdf = Pdf::loadView('customer.orders.invoice', compact('order', 'subtotal', 'total'))
            ->setPaper('a4');

        return $pdf->download("invoice-order-{$order->id}.pdf");
    } catch (\Throwable $e) {
        \Log::error('INVOICE PDF ERROR', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);

        abort(500, 'PDF generation failed. Check laravel.log');
    }
}




}
