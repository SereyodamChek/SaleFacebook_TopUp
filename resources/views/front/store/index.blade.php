@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/store.css') }}">
@endpush

@section('content')
<div class="store-wrap">

  {{-- TOP SERVICE TILES --}}
  <div class="service-row">
    <a class="service-card service-green" href="{{ route('store.index', ['group' => $selectedGroup]) }}">
      <div class="service-ico">
        <i class="fa-brands fa-facebook-f"></i>
      </div>
      <div class="service-txt">
        <div class="service-title">Check live on FB</div>
        <div class="service-sub">Free</div>
      </div>
    </a>

    <a class="service-card service-blue" href="{{ route('store.index', ['group' => $selectedGroup]) }}">
      <div class="service-ico">
        <i class="fa-solid fa-shield-halved"></i>
      </div>
      <div class="service-txt">
        <div class="service-title">Get 2FA code</div>
        <div class="service-sub">Free</div>
      </div>
    </a>

    <a class="service-card service-purple" href="{{ route('store.index', ['group' => $selectedGroup]) }}">
      <div class="service-ico">
        <i class="fa-brands fa-facebook"></i>
      </div>
      <div class="service-txt">
        <div class="service-title">Facebook icon</div>
        <div class="service-sub">Free</div>
      </div>
    </a>

    <a class="service-card service-orange" href="{{ route('store.index', ['group' => $selectedGroup]) }}">
      <div class="service-ico">
        <i class="fa-solid fa-user"></i>
      </div>
      <div class="service-txt">
        <div class="service-title">Random Face</div>
        <div class="service-sub">Free</div>
      </div>
    </a>
  </div>

  {{-- PAGE GRID: PRODUCTS + RIGHT PANEL --}}
  <div class="store-grid">

    {{-- LEFT: Products area --}}
    <div class="store-left">

      {{-- CATEGORY BAR --}}
      <div class="category-bar">
        <div class="category-bar-title">
          <i class="fa-brands fa-facebook"></i>
          <span>{{ strtoupper($selectedGroup) }} / Services</span>
        </div>

        <div class="category-scroll">
          {{-- "All" --}}
          <a class="cat-pill {{ !$selectedCategoryId && !$selectedItemId ? 'is-active' : '' }}"
             href="{{ route('store.index', ['group' => $selectedGroup]) }}">
            All
          </a>

          @foreach($categories as $cat)
            <a class="cat-pill {{ (string)$selectedCategoryId === (string)$cat->id ? 'is-active' : '' }}"
               href="{{ route('store.index', ['group' => $selectedGroup, 'cat' => $cat->id]) }}">
              {{ $cat->title }}
            </a>
          @endforeach
        </div>
      </div>

      {{-- SUB MENU ITEMS (when a category is selected) --}}
      @if($selectedCategoryId)
        @php
          $selectedCat = $categories->firstWhere('id', (int)$selectedCategoryId);
        @endphp

        @if($selectedCat)
          <div class="item-bar">
            <a class="item-pill {{ !$selectedItemId ? 'is-active' : '' }}"
               href="{{ route('store.index', ['group' => $selectedGroup, 'cat' => $selectedCat->id]) }}">
              All Items
            </a>

            @foreach($selectedCat->items as $it)
              <a class="item-pill {{ (string)$selectedItemId === (string)$it->id ? 'is-active' : '' }}"
                 href="{{ route('store.index', ['group' => $selectedGroup, 'item' => $it->id]) }}">
                @if($it->icon)
                  <img class="item-icon" src="{{ asset('storage/'.$it->icon) }}" alt="">
                @else
                  <span class="dot"></span>
                @endif
                <span>{{ $it->title }}</span>
                @if($it->status)
                  <span class="mini-badge mini-{{ $it->status_type ?? 'primary' }}">{{ $it->status }}</span>
                @endif
              </a>
            @endforeach
          </div>
        @endif
      @endif

      {{-- PRODUCTS GRID --}}
      <div class="product-grid">
        @forelse($products as $p)
          <div class="product-card {{ ((int)$p->stock <= 0) ? 'is-out' : '' }}">

            <div class="product-title">{{ $p->title }}</div>

            <div class="product-meta">
              <span class="tag">Stock: {{ (int)$p->stock }}</span>
              <span class="tag tag-blue">Sold: {{ (int)$p->sold_out_amount }}</span>
            </div>

            <div class="product-price">${{ number_format((float)$p->price, 2) }}</div>

            <div class="product-desc">
              {{ \Illuminate\Support\Str::limit(strip_tags($p->description), 120) }}
            </div>

   <div class="product-actions">
  <a href="{{ route('store.product.show', $p->id) }}" class="btn-soft">Detail</a>

  @if((int)$p->stock <= 0)
    <button class="btn-buy" type="button" disabled style="opacity:.35; pointer-events:none;">BUY NOW</button>
    <div class="out-stock-bar">OUT OF STOCK</div>
  @else
    {{-- ✅ Add to cart --}}
    <form method="POST" action="{{ route('cart.add', $p->id) }}" style="display:inline;">
      @csrf
      <input type="hidden" name="qty" value="1">
      <button class="btn-buy" type="submit">ADD TO CART</button>
    </form>

    {{-- ✅ Optional: Buy Now (add then go checkout) --}}
    <form method="POST" action="{{ route('cart.add', $p->id) }}" style="display:inline;">
      @csrf
      <input type="hidden" name="qty" value="1">
      <input type="hidden" name="redirect" value="checkout">
      <button class="btn-soft" type="submit">BUY NOW</button>
    </form>
  @endif
</div>



          </div>
        @empty
          <div class="empty">
            No products found.
          </div>
        @endforelse
      </div>

      <div class="pager">
        {{ $products->links() }}
      </div>

    </div>

    {{-- RIGHT: Balance panel --}}
    <aside class="store-right">
      <div class="balance-card">
        <div class="balance-title">Current balance</div>
        <div class="balance-amount">${{ number_format($balance['current'], 2) }}</div>
      </div>

      <div class="balance-box">
        <div class="label">Total Deposit</div>
        <div class="value">${{ number_format($balance['deposit'], 2) }}</div>
      </div>

      <div class="balance-box">
        <div class="label">Used Balance</div>
        <div class="value">${{ number_format($balance['used'], 2) }}</div>
      </div>

      <div class="balance-box">
        <div class="label">Discount</div>
        <div class="value">{{ (int)$balance['discount'] }}%</div>
      </div>


    </aside>

  </div>
</div>
@endsection