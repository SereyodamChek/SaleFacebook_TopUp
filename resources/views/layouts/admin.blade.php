@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin.css') }}">
@endpush

@section('content')

<div class="admin-wrap">

  {{-- Topbar --}}
  <div class="admin-top">
    <div>
      <div class="admin-title">@yield('page_title', 'Admin')</div>
      <div class="admin-sub">
        Logged in as <b>{{ auth()->user()->name }}</b> ({{ auth()->user()->email }})
      </div>
    </div>

    <div style="display:flex; gap:10px; align-items:center;">
      @yield('top_actions')

      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="logout-btn">Logout</button>
      </form>
    </div>
  </div>

  <div class="grid">
    {{-- Sidebar --}}
    <aside class="panel sidebar">
      <div class="brand">
        <div class="logo" aria-hidden="true"></div>
        <div>
          <div class="name">ADMIN PANEL</div>
          <div class="tag">Manage your store</div>
        </div>
      </div>

      <nav class="side-menu">
        <a class="side-link {{ request()->routeIs('admin.dashboard') ? 'is-active' : '' }}"
           href="{{ route('admin.dashboard') }}">
          <span class="side-ico">🏠</span><span class="side-text">Dashboard</span>
        </a>

        <a class="side-link {{ request()->routeIs('admin.users.index') ? 'is-active' : '' }}"
           href="{{ route('admin.users.index') }}">
          <span class="side-ico">👤</span><span class="side-text">Users</span>
        </a>
        <a class="side-link {{ request()->routeIs('admin.wallets.*') ? 'is-active' : '' }}"
   href="{{ route('admin.wallets.index') }}">
  <span class="side-ico"><i class="fa-solid fa-wallet"></i></span>
  <span class="side-text">Customer Wallets</span>
</a>


        <a class="side-link {{ request()->routeIs('admin.menu.categories.index') ? 'is-active' : '' }}"
           href="{{ route('admin.menu.categories.index') }}">
          <span class="side-ico"><i class="fa-solid fa-layer-group"></i></span>
          <span class="side-text">Menu Categories</span>
        </a>

        <a class="side-link {{ request()->routeIs('admin.products.*') ? 'is-active' : '' }}"
           href="{{ route('admin.products.index') }}">
          <span class="side-ico"><i class="fa-solid fa-box"></i></span>
          <span class="side-text">Products</span>
        </a>
      </nav>
    </aside>

    {{-- Page content --}}
    <main class="panel main">
      @yield('admin_content')
    </main>
  </div>

</div>

{{-- ✅ Render modals OUTSIDE .admin-wrap to avoid overflow/stacking/click issues --}}
@stack('modals')

{{-- Optional: a dedicated admin scripts stack (use @push('admin_scripts') in admin pages if you want) --}}
@stack('admin_scripts')

@endsection
