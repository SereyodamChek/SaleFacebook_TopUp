@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/store.css') }}">
@endpush

@section('content')
<div class="store-wrap">
  <div class="card" style="padding:18px;">

    <div style="display:flex; justify-content:space-between; align-items:center;">
      <h2 style="margin:0;">Checkout</h2>
      <a href="{{ route('cart.index') }}" class="btn-soft">Back to cart</a>
    </div>

    @if(session('error'))
      <div style="margin-top:12px;" class="alert danger">{{ session('error') }}</div>
    @endif

    @if(session('success'))
      <div style="margin-top:12px;" class="alert success">{{ session('success') }}</div>
    @endif

    <div style="height:1px; background:#eee; margin:14px 0;"></div>

    {{-- ✅ DB cart items --}}
    @if(!isset($items) || $items->isEmpty())
      <div style="color:#6b7280;">Your cart is empty.</div>
    @else

      <div style="display:flex; flex-direction:column; gap:10px;">
        @foreach($items as $item)
          <div style="display:flex; justify-content:space-between; gap:12px;">
            <div style="font-weight:700;">
              {{ $item->product_title ?? ($item->product->title ?? 'Product') }}
              <span style="color:#6b7280; font-weight:600;">× {{ (int)$item->qty }}</span>
            </div>

            <div style="font-weight:800;">
              ${{ number_format((float)$item->price * (int)$item->qty, 2) }}
            </div>
          </div>
        @endforeach
      </div>

      <div style="height:1px; background:#eee; margin:14px 0;"></div>

      <div style="display:flex; justify-content:space-between; align-items:center;">
        <div style="font-weight:900; font-size:18px;">Total</div>
        <div style="font-weight:900; font-size:18px;">
          ${{ number_format((float)$total, 2) }}
        </div>
      </div>

      <form method="POST" action="{{ route('checkout.pay') }}" style="margin-top:14px;">
        @csrf
        <button class="btn-buy" type="submit">Pay Now</button>
        <a href="{{ route('store.index') }}" class="btn-soft" style="margin-left:10px;">Continue shopping</a>
      </form>

    @endif

  </div>
</div>
@endsection
