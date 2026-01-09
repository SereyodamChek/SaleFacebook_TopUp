@extends('layouts.account')

@section('page_title', 'Order History')

@section('account_content')

<section class="card">
  <div class="card-header">
    <div class="card-title-wrap">
      <h2 class="card-title">Order History</h2>
      <div class="title-underline"></div>
    </div>
  </div>

  <div class="card-divider"></div>

  @if($orders->isEmpty())
    <div class="empty-state">
      <p>You have no orders yet.</p>
      <a href="{{ route('store.index') }}" class="btn btn-primary">Start shopping</a>
    </div>
  @else
    <div class="table-wrap">
      <table class="table">
        <thead>
          <tr>
            <th>#</th>
            <th>Date</th>
            <th>Items</th>
            <th>Total</th>
            <th>Status</th>
            <th></th>
          </tr>
        </thead>

        <tbody>
          @foreach($orders as $order)
            <tr>
              <td>#{{ $order->id }}</td>

              <td>
                {{ $order->created_at->format('Y-m-d H:i') }}
              </td>

              <td>
                {{ $order->items->count() }}
              </td>

              <td>
                <strong>${{ number_format($order->total_amount, 2) }}</strong>
              </td>

              @php
  $statusClass = match($order->status) {
    'paid'      => 'success',
    'pending'   => 'warning',
    'cancelled' => 'danger',
    'refunded'  => 'secondary',
    default     => 'primary',
  };
@endphp

<td>
  <span class="pill pill-{{ $statusClass }}">
    {{ ucfirst($order->status) }}
  </span>
</td>


              <td class="text-right">
              <a href="{{ route('customer.orders.show', $order->id) }}" class="btn btn-ghost">
  View
</a>

              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <div class="pager">
      {{ $orders->links() }}
    </div>
  @endif
</section>

@endsection
