@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/store.css') }}">
@endpush

@section('content')
<div class="store-wrap">
  <div class="store-grid">
    <div class="store-left">

      <div class="card" style="padding:18px;">
        <div style="display:flex; justify-content:space-between; align-items:center;">
          <h2 style="margin:0;">My Cart</h2>
          {{-- <a href="{{ route('store.index') }}" class="btn-soft">Continue shopping</a> --}}
        </div>

        @if(session('success'))
          <div style="margin-top:12px;" class="alert success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
          <div style="margin-top:12px;" class="alert danger">{{ session('error') }}</div>
        @endif

        <div style="height:1px; background:#eee; margin:14px 0;"></div>

        {{-- ✅ DB cart: use $items --}}
        @if(!isset($items) || $items->isEmpty())
          <div style="color:#6b7280;">Your cart is empty.</div>
        @else

          {{-- ✅ Update quantities FORM (only one) --}}
          <form method="POST" action="{{ route('cart.update') }}" id="updateCartForm">
            @csrf
          </form>

          <div style="overflow:auto;">
            <table style="width:100%; border-collapse:collapse;">
              <thead>
                <tr style="background:#f3f4f6;">
                  <th style="text-align:left; padding:12px;">Product</th>
                  <th style="text-align:left; padding:12px; width:140px;">Price</th>
                  <th style="text-align:left; padding:12px; width:140px;">Qty</th>
                  <th style="text-align:left; padding:12px; width:160px;">Subtotal</th>
                  <th style="padding:12px; width:160px;"></th>
                </tr>
              </thead>

              <tbody>
                @foreach($items as $item)
                  @php
                    $pid = $item->product_id;
                    $stock = (int)($item->product->stock ?? 0);
                  @endphp

                  <tr style="border-bottom:1px solid #eee;">
                    <td style="padding:12px;">
                      <div style="font-weight:800;">{{ $item->product->title ?? 'Product' }}</div>
                      @if($stock <= 0)
                        <div style="color:#ef4444; font-size:12px; margin-top:4px;">Out of stock</div>
                      @endif
                    </td>

                    <td style="padding:12px;">
                      ${{ number_format((float)$item->price, 2) }}
                    </td>

                    <td style="padding:12px;">
                      <input
                        type="number"
                        min="1"
                        max="{{ max(1, $stock) }}"
                        name="items[{{ $pid }}]"
                        value="{{ (int)$item->qty }}"
                        form="updateCartForm"
                        style="width:90px; height:38px; border-radius:8px; border:1px solid #ddd; padding:0 10px;"
                      >
                    </td>

                    <td style="padding:12px;">
                      ${{ number_format((float)$item->price * (int)$item->qty, 2) }}
                    </td>

                    <td style="padding:12px; text-align:right; white-space:nowrap;">
                      {{-- ✅ Reliable remove form (NOT hidden) --}}
                      <form method="POST" action="{{ route('cart.remove', $pid) }}" style="display:inline;">
                        @csrf
                        <button class="btn-soft" type="submit">Remove</button>
                      </form>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>

        <div style="display:flex; justify-content:flex-end; align-items:center; gap:10px; margin-top:14px;">
  <button class="btn-soft" type="submit" form="updateCartForm">Update cart</button>

  <a href="{{ route('checkout.index') }}"
     class="btn-buy"
     style="text-decoration:none; display:inline-flex; align-items:center; justify-content:center;">
    BUY NOW
  </a>
</div>


        @endif
      </div>

    </div>

    <aside class="store-right">
      <div class="balance-card">
        <div class="balance-title">Cart total</div>
        <div class="balance-amount">${{ number_format($total ?? 0, 2) }}</div>
      </div>

      <a href="{{ route('checkout.index') }}" class="btn-buy"
         style="margin-top:12px; display:flex; justify-content:center; text-decoration:none;">
        BUY NOW
      </a>

      <a href="{{ route('store.index') }}" class="btn-soft"
         style="margin-top:10px; display:flex; justify-content:center; text-decoration:none;">
        Add more items
      </a>
    </aside>

  </div>
</div>
@endsection
