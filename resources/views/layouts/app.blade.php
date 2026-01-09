<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" href="{{ asset('/images/farvo.png') }}" type="image/png">

  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Marketing Electronic Commerce</title>

  <link rel="dns-prefetch" href="//fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
  <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        crossorigin="anonymous" referrerpolicy="no-referrer"/>

  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
  @stack('styles')
</head>

<body>
<header class="header">
  <div class="wrap">

    <!-- ROW 1 -->
    <div class="row1">
      <div class="brand">
        <a href="/">
          <img src="{{ asset('images/logo_light_OYM.png') }}" alt="ADCAMADS.COM" />
        </a>
      </div>

      <div class="search">
        <input type="text" placeholder="Search for products..." />
        <span class="search-ico" aria-hidden="true">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="11" cy="11" r="7"></circle>
            <path d="M20 20l-3.5-3.5"></path>
          </svg>
        </span>
      </div>

      <div class="actions">
        {{-- MOBILE: hamburger + cart --}}
        <div class="mobile-header">
          <button class="m-btn"
                  type="button"
                  data-bs-toggle="offcanvas"
                  data-bs-target="#mobileMenu"
                  aria-controls="mobileMenu"
                  title="Menu">
            ☰
          </button>

          <a href="{{ route('cart.index') }}" class="m-btn" title="Cart" style="text-decoration:none;">
            🛒
          </a>
        </div>

        {{-- DESKTOP: icons --}}
        <a href="{{ route('cart.index') }}" class="icon-btn" title="Cart">
          🛒
          @if(($cartCount ?? 0) > 0)
            <span class="badge">{{ $cartCount }}</span>
          @endif
        </a>

        <button type="button"
                class="icon-btn"
                title="Bank"
                data-bs-toggle="offcanvas"
                data-bs-target="#rechargeCanvas"
                aria-controls="rechargeCanvas"
                data-bs-backdrop="static">
          🏛️
        </button>

        @auth
          <a href="{{ route('customer.profile.edit') }}" class="user-card-link">
            <div class="user">
              <div class="avatar">
                {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
              </div>
              <div class="meta">
                <div class="name">{{ auth()->user()->name }}</div>
                <div class="money">
                  ${{ number_format(optional(auth()->user()->wallet)->balance ?? 0, 2) }}
                </div>
              </div>
            </div>
          </a>
        @else
          <a href="{{ route('login') }}" class="user-card-link">
            <div class="user">
              <div class="avatar">G</div>
              <div class="meta">
                <div class="name">Guest</div>
                <div class="money">Login</div>
              </div>
            </div>
          </a>
        @endauth
      </div>
    </div>

    <div class="divider"></div>

    <!-- ROW 2 -->
    <div class="row2">
      <nav class="menu" aria-label="Main menu">
        <a href="/">Gate</a>

        {{-- PRODUCT mega --}}
        <div class="menu-item has-mega">
          <a href="#" class="menu-link">Product <span class="caret">▼</span></a>

          <div class="mega">
            <div class="mega-inner">
              @foreach(($megaMenu['product'] ?? collect()) as $cat)
                <div class="mega-col">
                  <div class="mega-title">{{ $cat->title }}</div>
                  <div class="mega-line"></div>

                  @foreach($cat->items as $item)
                    @php $iconPath = $item->icon ? str_replace('\\','/',$item->icon) : null; @endphp

                    <a class="mega-link"
                       href="{{ route('store.index', ['group' => $cat->group_key, 'item' => $item->id]) }}">
                      <span class="mi-icon">
                        @if($iconPath)
                          <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($iconPath) }}"
                               alt="{{ $item->title }}" loading="lazy">
                        @else
                          <span class="mi-dot"></span>
                        @endif
                      </span>

                      <span class="mi-text">{{ $item->title }}</span>

                      @if($item->status)
                        <span class="badge badge-{{ $item->status_type ?? 'primary' }}">
                          {{ $item->status }}
                        </span>
                      @endif
                    </a>
                  @endforeach
                </div>
              @endforeach
            </div>
          </div>
        </div>

        {{-- RECHARGE dropdown --}}
        <div class="menu-item has-dropdown">
          <a href="#" class="menu-link">Recharge <span class="caret">▼</span></a>
          <div class="dropdown">
            <div class="dd-group">
              <div class="dd-title">Recharge</div>

              <a class="dd-link" href="{{ route('topup.create') }}">
                <span class="mi-icon"><span class="mi-dot"></span></span>
                <span class="mi-text">KHQR Payment</span>
                <span class="badge badge-success">Hot</span>
              </a>

              <a class="dd-link is-disabled" href="javascript:void(0)" aria-disabled="true">
                <span class="mi-icon"><span class="mi-dot"></span></span>
                <span class="mi-text">Crypto</span>
                <span class="badge badge-danger">Unavailable</span>
              </a>
            </div>
          </div>
        </div>

        {{-- Association dropdown --}}
        <div class="menu-item has-dropdown">
          <a href="#" class="menu-link">Association <span class="caret">▼</span></a>
          <div class="dropdown">
            <div class="dd-group">
              <div class="dd-title">Association</div>

              <a class="dd-link" href="{{ route('customer.orders.index') }}">
                <span class="mi-icon"><span class="mi-dot"></span></span>
                <span class="mi-text">Order History</span>
                <span class="badge badge-success">Hot</span>
              </a>

              <a class="dd-link" href="{{ route('customer.activity.index') }}">
                <span class="mi-icon"><span class="mi-dot"></span></span>
                <span class="mi-text">Activity Log</span>
              </a>
            </div>
          </div>
        </div>
      </nav>

      <div class="contacts">
        <div class="contact-item">
          <div class="ico" aria-hidden="true">📞</div>
          <div class="txt">
            <div class="label">Group</div>
            <div class="value">Telegram: @admin</div>
          </div>
        </div>

        <div class="contact-item">
          <div class="ico" aria-hidden="true">✉️</div>
          <div class="txt">
            <div class="label">E-mail</div>
            <div class="value">hello@admin.com</div>
          </div>
        </div>
      </div>
    </div>

  </div>
</header>

<main class="py-4">
  @yield('content')
</main>

<footer class="footer">
  <div class="footer-top">
    <div class="container">

      <div class="footer-col brand">
        <img src="{{ asset('images/logo_light_OYM.png') }}" alt="ADCAMADS" class="footer-logo">
        <p class="footer-desc">Automatic, reputable, cheap ADS raw material selling system...</p>
      </div>

      <div class="footer-col">
        <h4>Contact</h4>
        <div class="contact-item"><span class="icon">✉️</span><span>info@example.com</span></div>
        <div class="contact-item"><span class="icon">📞</span><span>Telegram: @admin</span></div>
        <div class="contact-item"><span class="icon">📍</span><span>Phnom Penh, Cambodia</span></div>
      </div>

     <div class="footer-col">
  <h4>Links</h4>

  <ul class="footer-links">

    {{-- Gate --}}
    <li>
      <a href="{{ url('/') }}">Gate</a>
    </li>

    {{-- Product (link to main product page) --}}
    <li>
      <a href="{{ route('store.index', ['group' => 'product']) }}">
        Product
      </a>
    </li>

    {{-- Recharge --}}
    <li>
      <a href="{{ route('topup.create') }}">
        Recharge
      </a>
    </li>


   

  </ul>
</div>


    </div>
  </div>

  <div class="footer-bottom">
    <div class="container bottom-content">
      <p>© All Copyrights Reserved by AdcamAds Software By AI Brain Tech</p>
      <div class="payments">
        <img src="{{ asset('images/payments/01.jpg') }}" alt="PayPal">
        <img src="{{ asset('images/payments/02.jpg') }}" alt="Maestro">
        <img src="{{ asset('images/payments/03.jpg') }}" alt="Discover">
        <img src="{{ asset('images/payments/04.jpg') }}" alt="Visa">
      </div>
    </div>
  </div>

  <a href="#" class="back-to-top">↑</a>
</footer>

{{-- JS --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@stack('scripts')

@if (session('success'))
  <script>
    Swal.fire({ toast:true, position:'top-end', icon:'success', title:'{{ session('success') }}', showConfirmButton:false, timer:3000 });
  </script>
@endif

@if (session('error'))
  <script>
    Swal.fire({ icon:'error', title:'Error', text:'{{ session('error') }}' });
  </script>
@endif

{{-- Offcanvas: Recharge (Bootstrap only; removed custom JS that conflicts) --}}
<div class="offcanvas offcanvas-end" tabindex="-1" id="rechargeCanvas" aria-labelledby="rechargeTitle">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title dd-title" id="rechargeTitle">Select deposit method</h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>

  <div class="offcanvas-body">
    {{-- IMPORTANT: remove data-bs-dismiss so navigation always works --}}
    <a class="dd-link" href="{{ route('topup.create') }}">
      <span class="mi-icon"><span class="mi-dot"></span></span>
      <span class="mi-text">KHQR Payment</span>
      <span class="badge badge-success">Hot</span>
    </a>

    <a class="dd-link is-disabled" href="javascript:void(0)" aria-disabled="true">
      <span class="mi-icon"><span class="mi-dot"></span></span>
      <span class="mi-text">Crypto</span>
      <span class="badge badge-danger">Unavailable</span>
    </a>
  </div>
</div>

{{-- Float menu --}}
@if(isset($categories) && $categories)
  @php $allItems = $categories->flatMap(fn($c) => $c->items ?? collect()); @endphp

  <div class="float-menu">
    @foreach($allItems as $it)
      @php
        $iconPath = $it->icon ? str_replace('\\','/',$it->icon) : null;
        $isActive = (string)($selectedItemId ?? '') === (string)$it->id;
      @endphp

      <a class="float-item {{ $isActive ? 'is-active' : '' }}"
         href="{{ route('home', ['group' => ($selectedGroup ?? null), 'item' => $it->id]) }}"
         title="{{ $it->title }}">
        <span class="float-label">{{ $it->title }}</span>
        <span class="float-ico">
          @if($iconPath)
            <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($iconPath) }}" alt="{{ $it->title }}">
          @else
            <img src="{{ asset('images/default-item.png') }}" alt="Item">
          @endif
        </span>
      </a>
    @endforeach
  </div>
@endif

{{-- ===== Mobile Bottom Nav ===== --}}
<div class="mobile-nav">
  <div class="mobile-nav-inner">

    <a class="mnav-item {{ request()->routeIs('customer.profile.edit') ? 'is-active' : '' }}"
       href="{{ auth()->check() ? route('customer.profile.edit') : route('login') }}">
      <span class="mnav-ico">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M20 21a8 8 0 0 0-16 0"></path>
          <circle cx="12" cy="8" r="4"></circle>
        </svg>
      </span>
      <span>Profile</span>
    </a>

    <a class="mnav-item {{ request()->routeIs('cart.index') ? 'is-active' : '' }}"
       href="{{ route('cart.index') }}">
      <span class="mnav-ico mnav-ico--cart">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <circle cx="9" cy="20" r="1"></circle>
          <circle cx="17" cy="20" r="1"></circle>
          <path d="M3 4h2l2.4 12.5a2 2 0 0 0 2 1.5h7.6a2 2 0 0 0 2-1.6L22 8H6"></path>
        </svg>

        @if(($cartCount ?? 0) > 0)
          <span class="cart-badge">{{ $cartCount }}</span>
        @endif
      </span>
      <span>Cart</span>
    </a>

    <div class="mnav-center-wrap">
      <a class="mnav-center" href="{{ route('home') }}" aria-label="Home">
        <svg viewBox="0 0 24 24" fill="none" stroke-width="2">
          <path d="M3 10.5L12 3l9 7.5"></path>
          <path d="M5 10v10h14V10"></path>
        </svg>
      </a>
      <div class="mnav-center-label">Home</div>
    </div>

    @guest
      <a class="mnav-item {{ request()->routeIs('register') ? 'is-active' : '' }}" href="{{ route('register') }}">
        <span class="mnav-ico">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
            <circle cx="8.5" cy="7" r="3.5"></circle>
            <path d="M20 8v6"></path>
            <path d="M23 11h-6"></path>
          </svg>
        </span>
        <span>Register</span>
      </a>

      <a class="mnav-item {{ request()->routeIs('login') ? 'is-active' : '' }}" href="{{ route('login') }}">
        <span class="mnav-ico">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
            <path d="M10 17l5-5-5-5"></path>
            <path d="M15 12H3"></path>
          </svg>
        </span>
        <span>Login</span>
      </a>
    @else
      <a class="mnav-item {{ request()->routeIs('customer.orders.index') ? 'is-active' : '' }}"
         href="{{ route('customer.orders.index') }}">
        <span class="mnav-ico">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M9 6h11"></path>
            <path d="M9 12h11"></path>
            <path d="M9 18h11"></path>
            <path d="M4 6h.01"></path>
            <path d="M4 12h.01"></path>
            <path d="M4 18h.01"></path>
          </svg>
        </span>
        <span>Orders</span>
      </a>

      <form method="POST" action="{{ route('logout') }}" style="margin:0;">
        @csrf
        <button type="submit" class="mnav-item" style="border:0;background:transparent;width:100%;padding:0;">
          <span class="mnav-ico">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
              <path d="M16 17l5-5-5-5"></path>
              <path d="M21 12H9"></path>
            </svg>
          </span>
          <span>Logout</span>
        </button>
      </form>
    @endguest

  </div>
</div>

{{-- MOBILE LEFT OFFCANVAS MENU --}}
<div class="offcanvas offcanvas-start" tabindex="-1" id="mobileMenu" aria-labelledby="mobileMenuLabel">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title" id="mobileMenuLabel">Menu</h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>

  <div class="offcanvas-body">
    <div style="display:flex; flex-direction:column; gap:10px;">

      <div class="dd-title" style="margin-top:10px;">Product</div>
      @foreach(($megaMenu['product'] ?? collect()) as $cat)
        @foreach($cat->items as $item)
          @php $iconPath = $item->icon ? str_replace('\\','/',$item->icon) : null; @endphp

          {{-- IMPORTANT: removed data-bs-dismiss so link always navigates --}}
          <a class="dd-link"
             href="{{ route('store.index', ['group' => $cat->group_key, 'item' => $item->id]) }}">
            <span class="mi-icon">
              @if($iconPath)
                <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($iconPath) }}"
                     alt="{{ $item->title }}"
                     style="width:18px;height:18px;border-radius:6px;object-fit:cover;">
              @else
                <span class="mi-dot"></span>
              @endif
            </span>

            <span class="mi-text">{{ $item->title }}</span>

            @if($item->status)
              <span class="badge badge-{{ $item->status_type ?? 'primary' }}">
                {{ $item->status }}
              </span>
            @endif
          </a>
        @endforeach
      @endforeach

      <div class="dd-title" style="margin-top:14px;">Recharge</div>

      {{-- IMPORTANT: removed data-bs-dismiss so link always navigates --}}
      <a class="dd-link" href="{{ route('topup.create') }}">
        <span class="mi-icon"><span class="mi-dot"></span></span>
        <span class="mi-text">KHQR Payment</span>
        <span class="badge badge-success">Hot</span>
      </a>

    </div>
  </div>
</div>

{{-- Toggle body class while any offcanvas is open (so your CSS can disable float/mobile clicks) --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
  const els = document.querySelectorAll('.offcanvas');
  els.forEach((el) => {
    el.addEventListener('shown.bs.offcanvas', () => document.body.classList.add('offcanvas-open'));
    el.addEventListener('hidden.bs.offcanvas', () => document.body.classList.remove('offcanvas-open'));
  });
});
</script>

</body>
</html>
