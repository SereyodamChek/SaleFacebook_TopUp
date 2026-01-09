
<div class="brand">
  <div class="logo"></div>
  <div>
    <div class="name">MY ACCOUNT</div>
    <div class="tag">Customer dashboard</div>
  </div>
</div>

<nav class="side-menu">
  {{-- Profile --}}
  <a class="side-link {{ request()->routeIs('customer.profile.edit') ? 'is-active' : '' }}"
     href="{{ route('customer.profile.edit') }}">
    <span class="side-ico"><i class="fa-solid fa-user"></i></span>
    <span class="side-text">Personal information</span>
  </a>

 

  {{-- Order History --}}
<a class="side-link {{ request()->routeIs('customer.orders.*') ? 'is-active' : '' }}"
   href="{{ route('customer.orders.index') }}">
  <span class="side-ico"><i class="fa-solid fa-box-archive"></i></span>
  <span class="side-text">Order History</span>
</a>

  {{-- Activity --}}
  <a class="side-link {{ request()->routeIs('customer.activity.index') ? 'is-active' : '' }}"
   href="{{ route('customer.activity.index') }}">
  <span class="side-ico"><i class="fa-solid fa-clock-rotate-left"></i></span>
  <span class="side-text">Activity Log</span>
</a>


{{-- Logout --}}
<form method="POST" action="{{ route('logout') }}" style="margin:0;">
  @csrf

  <button type="submit" class="side-link logout-link">
    <span class="side-ico">
      <i class="fa-solid fa-right-from-bracket"></i>
    </span>
    <span class="side-text">Logout</span>
  </button>
</form>

</nav>
