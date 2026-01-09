@extends('layouts.admin')

@section('page_title', 'Admin Dashboard')

@section('admin_content')
  <div class="stats">
    <div class="stat"><div class="label">Total Users</div><div class="value">0</div></div>
    <div class="stat"><div class="label">Total Orders</div><div class="value">0</div></div>
    <div class="stat"><div class="label">Pending Orders</div><div class="value">0</div></div>
    <div class="stat"><div class="label">Revenue</div><div class="value">$0.00</div></div>
  </div>

  <div class="panel box" style="box-shadow:none;">
    <h3>Recent Orders</h3>
    <p>Later you can replace data with real database orders.</p>
  </div>
@endsection
