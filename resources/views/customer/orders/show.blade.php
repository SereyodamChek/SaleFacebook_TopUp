@extends('layouts.account')

@section('page_title', 'Order #'.$order->id)

@section('account_content')

<section class="card">
  <div class="card-header">
    <div class="card-title-wrap">
      <h2 class="card-title">Order #{{ $order->id }}</h2>
      <div class="title-underline"></div>
    </div>

   <a href="{{ route('customer.orders.invoice', $order->id) }}" class="btn btn-primary">
  Download Invoice (PDF)
</a>

  </div>

  <div class="card-divider"></div>

  <div class="order-meta">
    <div><strong>Date:</strong> {{ $order->created_at->format('Y-m-d H:i') }}</div>
    <div><strong>Status:</strong> {{ ucfirst($order->status) }}</div>
    <div><strong>Total:</strong> ${{ number_format($order->total_amount, 2) }}</div>
  </div>

  <div class="table-wrap">
    <table class="table">
      <thead>
        <tr>
          <th>Product</th>
          <th>Price</th>
          <th>Qty</th>
          <th>Subtotal</th>
        </tr>
      </thead>
      <tbody>
        @foreach($order->items as $item)
          <tr>
            <td>{{ $item->product->title ?? 'Product' }}</td>
            <td>${{ number_format($item->price, 2) }}</td>
            <td>{{ $item->qty }}</td>
            <td>${{ number_format($item->price * $item->qty, 2) }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</section>

@endsection
