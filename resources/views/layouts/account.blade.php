@extends('layouts.app')

@push('styles')
  <link rel="stylesheet" href="{{ asset('css/account.css') }}">
@endpush

@section('content')
<div class="account-wrap">

  {{-- Topbar --}}
  {{-- <div class="account-top">
    <div>
      <div class="account-title">@yield('page_title', 'My Account')</div>
      <div class="account-sub">
        Logged in as <b>{{ auth()->user()->name ?? 'User' }}</b>
        ({{ auth()->user()->email ?? '' }})
      </div>
    </div>

    <div style="display:flex; gap:10px; align-items:center;">
      @yield('top_actions')

      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="logout-btn">Logout</button>
      </form>
    </div>
  </div> --}}

  <div class="grid">
    {{-- Sidebar --}}
<aside class="panel sidebar">
    @include('customer.partials.account-sidebar')
</aside>


    {{-- Page content --}}
    <main class="panel main">
      @yield('account_content')
    </main>
  </div>

</div>

{{-- Keep modals outside wrapper (same as admin) --}}
@stack('modals')
@stack('account_scripts')
@endsection
