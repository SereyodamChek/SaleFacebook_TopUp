@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/store.css') }}">
@endpush

@section('content')
<div class="store-wrap">

  {{-- PAGE GRID: LEFT + RIGHT PANEL --}}
  <div class="store-grid">

    {{-- LEFT: Product detail --}}
    <div class="store-left">

      <div class="product-detail-card">

        <div class="pd-header">
          <h1 class="pd-title">{{ $product->title }}</h1>
          <div class="pd-price">${{ number_format((float)$product->price, 2) }}</div>
        </div>

        <div class="pd-meta">
          <span class="tag">Stock: {{ (int)$product->stock }}</span>
          <span class="tag tag-blue">Sold: {{ (int)$product->sold_out_amount }}</span>
        </div>

        <div class="pd-desc">
          {!! nl2br(e($product->description)) !!}
        </div>

        <div class="pd-actions">
  @if((int)$product->stock <= 0)
    <button class="btn-disabled" type="button">OUT OF STOCK</button>
  @else
    {{-- BUY NOW = add to cart then go to checkout --}}
    <form method="POST" action="{{ route('cart.add', $product->id) }}" style="display:inline;">
      @csrf
      <input type="hidden" name="qty" value="1">
      <button class="btn-buy" type="submit">
        BUY NOW
      </button>
    </form>
  @endif

  {{-- Go to cart / checkout --}}
  <a href="{{ route('cart.index') }}" class="btn-soft">
    Go to Cart
  </a>
</div>


      </div>

    </div>

    {{-- RIGHT: Balance panel (same as store index) --}}
    <aside class="store-right">
      <div class="balance-card">
        <div class="balance-title">Current balance</div>
        <div class="balance-amount">${{ number_format($balance['current'] ?? 0, 2) }}</div>
      </div>

      <div class="balance-box">
        <div class="label">Total Deposit</div>
        <div class="value">${{ number_format($balance['deposit'] ?? 0, 2) }}</div>
      </div>

      <div class="balance-box">
        <div class="label">Used Balance</div>
        <div class="value">${{ number_format($balance['used'] ?? 0, 2) }}</div>
      </div>

      <div class="balance-box">
        <div class="label">Discount</div>
        <div class="value">{{ (int)($balance['discount'] ?? 0) }}%</div>
      </div>

      {{-- <div class="user-card">
        <div class="avatar">{{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}</div>
        <div>
          <div class="uname">{{ $user->name ?? 'Guest' }}</div>
          <div class="uemail">{{ $user->email ?? '' }}</div>
        </div>
      </div> --}}
    </aside>

  </div>
</div>
@endsection
