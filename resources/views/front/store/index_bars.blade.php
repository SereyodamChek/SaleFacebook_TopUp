@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/store.css') }}">
<link rel="stylesheet" href="{{ asset('css/store-bars.css') }}">

<style>
  /* ====== TOP NOTICE CARD ====== */
  .notice-card{
    background:#fff;
    border-radius:14px;
    box-shadow: 0 12px 22px rgba(0,0,0,.10);
    border: 1px solid #eef3f9;
    padding: 26px 26px 22px;
    margin: 0 0 18px;
  }
  .notice-note{
    color:#e53935;
    font-weight:800;
    font-size: 18px;
    letter-spacing: .2px;
    margin-bottom: 16px;
  }
  .notice-line{
    font-size: 22px;
    font-weight: 900;
    color:#222;
    margin: 8px 0;
    line-height: 1.25;
  }
  .notice-line a{
    color:#1d63d8;
    text-decoration:none;
    font-weight: 900;
  }
  .notice-line a:hover{ text-decoration: underline; }
  .notice-text{
    margin-top: 12px;
    font-size: 18px;
    color:#333;
    line-height: 1.6;
  }
  .notice-text .label{ font-weight: 900; text-decoration: underline; }
  .notice-text .strong{ font-weight: 900; }
  .notice-text .muted{ color:#444; }
  .notice-text p{ margin: 10px 0; }

  /* ====== MENU BAR LIST (like screenshot) ====== */
  .menu-list{
    display:flex;
    flex-direction:column;
    gap: 16px;
  }

  .menu-bar{
    display:block;
    text-decoration:none;
    border-radius: 12px;
    overflow:hidden;
    box-shadow: 0 10px 18px rgba(0,0,0,.18);
  }
  .menu-bar-inner{
    display:flex;
    align-items:center;
    gap: 14px;
    height: 72px;
    padding: 0 18px 0 14px;
    background: linear-gradient(90deg, #0b2b8f 0%, #0d6db3 55%, #14b6c9 100%);
  }

  .menu-bar-icon{
    width: 46px;
    height: 46px;
    background:#fff;
    border-radius:10px;
    display:flex;
    align-items:center;
    justify-content:center;
    overflow:hidden;
    box-shadow: inset 0 0 0 1px rgba(0,0,0,.08);
    flex: 0 0 46px;
  }
  .menu-bar-icon img{
    width:100%;
    height:100%;
    object-fit:cover;
  }

  .menu-bar-title{
    color:#fff;
    font-size:22px;
    font-weight:900;
    letter-spacing:.3px;
    text-transform: none;
    line-height: 1.1;
  }

  /* ====== PRODUCTS UNDER EACH MENU ====== */
  .item-products{
    margin: 10px 0 4px;
  }

  .pgrid{
    display:grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 14px;
    margin-top: 12px;
    margin-bottom: 18px;
  }
  @media(max-width:1100px){ .pgrid{ grid-template-columns: repeat(2, minmax(0,1fr)); } }
  @media(max-width:700px){ .pgrid{ grid-template-columns: 1fr; } }

  .pcard{
    border-radius: 14px;
    overflow:hidden;
    background:#fff;
    border: 1px solid #e6f1fb;
    box-shadow: 0 10px 18px rgba(0,0,0,.06);
    transition: .15s ease;
    display:flex;
    flex-direction:column;
    min-height: 250px;
  }
  .pcard:hover{ transform: translateY(-2px); }

  .pcard-top{
    position:relative;
    height: 110px;
    background: linear-gradient(135deg, #0b2b8f 0%, #0d6db3 55%, #14b6c9 100%);
  }
  .pcard-top::after{
    content:"";
    position:absolute; inset:0;
    background: radial-gradient(circle at 30% 30%, rgba(255,255,255,.25), transparent 55%);
  }

  .pbadge{
    position:absolute;
    top:10px; left:10px;
    font-size:11px;
    font-weight:900;
    padding:4px 10px;
    border-radius:999px;
    background: rgba(255,255,255,.88);
    color:#0b2b8f;
    z-index:2;
  }
  .pbadge.out{ color:#b10b0b; }

  .pprice{
    position:absolute;
    top:10px; right:10px;
    font-size:12px;
    font-weight:900;
    padding:6px 10px;
    border-radius: 10px;
    background: rgba(0,0,0,.22);
    color:#fff;
    z-index:2;
  }

  .pcard-body{
    padding: 12px 12px 10px;
    display:flex;
    flex-direction:column;
    gap: 8px;
    flex:1;
  }
  .ptitle{
    font-size:14px;
    font-weight:900;
    color:#111;
    line-height:1.2;
    min-height: 34px;
  }

  .pmeta{
    display:flex;
    gap:8px;
    flex-wrap:wrap;
  }
  .ptag{
    font-size:11px;
    font-weight:900;
    padding:4px 8px;
    border-radius:999px;
    background:#f2f7ff;
    color:#0b2b8f;
  }
  .pdesc{
    font-size:12px;
    color:#555;
    line-height:1.45;
    min-height: 42px;
  }

  .pcard-actions{
    display:flex;
    gap:8px;
    padding: 0 12px 12px;
    align-items:center;
    flex-wrap: wrap;
  }

  .btn-mini{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    padding: 9px 10px;
    border-radius: 10px;
    font-weight:900;
    font-size:12px;
    text-decoration:none;
    border: 1px solid #d7e8f7;
    background:#fff;
    color:#0b2b8f;
    transition:.15s ease;
  }
  .btn-mini:hover{ transform: translateY(-1px); }

  .btn-primary-mini{
    background: linear-gradient(90deg,#0b2b8f 0%,#0d6db3 55%,#14b6c9 100%);
    border-color: transparent;
    color:#fff;
  }

  .btn-disabled{ opacity:.4; pointer-events:none; }

  .empty-mini{
    padding: 10px 12px;
    color:#555;
    background:#fff;
    border:1px solid #eef3f9;
    border-radius: 12px;
    margin: 10px 0 18px;
  }
</style>
@endpush

@section('content')
<div class="store-wrap">

  {{-- TOP NOTICE / POLICY CARD --}}
  {{-- <div class="notice-card">
    <div class="notice-note">
      * Note: The System Will Automatically Delete Orders After 7 Days From The Time Of Purchase, Please Download Before The Above Deadline
    </div>

    <div class="notice-line">
      ID Card download URL:
      <a href="https://checkliveacc.com" target="_blank" rel="noopener">
        VIRTUAL ID-CARD DOWNLOADER (XMDT)
      </a>
    </div>

    <div class="notice-line" style="font-size:20px;font-weight:800;">
      Website Create Emboss - Get 2FA - Check Live - Read Hotmail ...
      <a href="https://checkliveacc.com" target="_blank" rel="noopener">CHECKLIVEACC.COM</a>
    </div>

    <div class="notice-text">
      <p>
        <span class="label">Warranty policy related to facebook marketplace:</span>
        <span class="muted">
          Currently, we only warranty Marketplace for products European countries, and we refuse to warranty other countries.
          Warranty content: we will refund or replace accounts that are locked from Marketplace before the customer makes a purchase
          (Including accounts without Marketplace features)
        </span>
      </p>

      <p>
        <span class="label">Warranty checkpoint phone code, locked :</span>
        <span class="strong"> ONLY WARRANTY IN 6H FROM PURCHASED TIME</span>
      </p>

      <p>
        <span class="label">Warranty accountquality DIE after sale:</span>
        <span class="strong"> NO</span>
      </p>

      <p class="strong">
        NO WARRANTY CHECKPOINT BECAUSE OF RESET PASSWORD BY EMAIL
      </p>
    </div> 
  </div> --}}

  {{-- PAGE GRID --}}
  <div class="store-grid">

    {{-- LEFT --}}
    <div class="store-left">

      @php
        $allItems = $categories->flatMap(fn($c) => $c->items ?? collect());
      @endphp

      <div class="menu-list">
        @forelse($allItems as $it)
          @php
            $iconPath = $it->icon ? str_replace('\\','/',$it->icon) : null;
            $itemProducts = $productsByItem[(string)$it->id] ?? collect();
          @endphp

          {{-- MENU BAR --}}
          <a class="menu-bar" href="{{ route('home', ['group' => $selectedGroup, 'item' => $it->id]) }}">
            <div class="menu-bar-inner">
              <span class="menu-bar-icon">
                @if($iconPath)
                  <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($iconPath) }}" alt="{{ $it->title }}">
                @else
                  <img src="{{ asset('images/default-item.png') }}" alt="Item">
                @endif
              </span>

              <div class="menu-bar-title">{{ $it->title }}</div>
            </div>
          </a>

          {{-- PRODUCTS INSIDE MENU ITEM --}}
          <div class="item-products">
            @if($itemProducts->isEmpty())
              <div class="empty-mini">No products found for this menu item.</div>
            @else
              <div class="pgrid">
                @foreach($itemProducts as $p)
                  @php $out = ((int)$p->stock <= 0); @endphp

                  <div class="pcard">
                    <div class="pcard-top">
                      <div class="pbadge {{ $out ? 'out' : '' }}">
                        {{ $out ? 'OUT OF STOCK' : 'IN STOCK' }}
                      </div>
                      <div class="pprice">${{ number_format((float)$p->price, 2) }}</div>
                    </div>

                    <div class="pcard-body">
                      <div class="ptitle">{{ $p->title }}</div>

                      <div class="pmeta">
                        <span class="ptag">Stock: {{ (int)$p->stock }}</span>
                        <span class="ptag">Sold: {{ (int)$p->sold_out_amount }}</span>
                      </div>

                      <div class="pdesc">
                        {{ \Illuminate\Support\Str::limit(strip_tags($p->description), 110) }}
                      </div>
                    </div>

                    <div class="pcard-actions">
                      <a class="btn-mini" href="{{ route('store.product.show', $p->id) }}">Detail</a>

                      @auth
                        @if($out)
                          <span class="btn-mini btn-primary-mini btn-disabled">Add to cart</span>
                          <span class="btn-mini btn-disabled">Buy now</span>
                        @else
                          <form method="POST" action="{{ route('cart.add', $p->id) }}" style="display:inline;">
                            @csrf
                            <input type="hidden" name="qty" value="1">
                            <button class="btn-mini btn-primary-mini" type="submit">Add to cart</button>
                          </form>

                          <form method="POST" action="{{ route('cart.add', $p->id) }}" style="display:inline;">
                            @csrf
                            <input type="hidden" name="qty" value="1">
                            <input type="hidden" name="redirect" value="checkout">
                            <button class="btn-mini" type="submit">Buy now</button>
                          </form>
                        @endif
                      @else
                        <a class="btn-mini btn-primary-mini" href="{{ route('login') }}">Login to buy</a>
                      @endauth
                    </div>
                  </div>
                @endforeach
              </div>
            @endif
          </div>

        @empty
          <div class="empty">No menu items found.</div>
        @endforelse
      </div>

    </div>

    {{-- RIGHT SIDEBAR --}}
    <aside class="store-right">
      @auth
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
      @else
        <div class="balance-card">
          <div class="balance-title">Welcome</div>
          <div class="balance-amount" style="font-size:18px;">Guest</div>
        </div>

        <div class="balance-box" style="display:block;">
          <div class="label" style="margin-bottom:10px;">Please login to buy products</div>

          <div style="display:flex; gap:10px;">
            <a href="{{ route('login') }}" class="btn-buy" style="text-decoration:none; display:inline-block;">
              Login
            </a>

            @if (Route::has('register'))
              <a href="{{ route('register') }}" class="btn-soft" style="text-decoration:none; display:inline-block;">
                Register
              </a>
            @endif
          </div>
        </div>
      @endauth
    </aside>

  </div>
</div>
@endsection
