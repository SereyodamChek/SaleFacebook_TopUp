@extends('layouts.admin')

@section('page_title', 'Customer Wallets')

@section('top_actions')
  <form method="GET" action="{{ route('admin.wallets.index') }}" style="display:flex; gap:10px;">
    <input class="input" name="q" value="{{ $q }}" placeholder="Search name or email..." style="width:260px;">
    <button class="btn-primary" type="submit">
      <i class="fa-solid fa-magnifying-glass"></i> Search
    </button>
  </form>
@endsection

@section('admin_content')


<div class="box" style="box-shadow:none;">
  <h3>All Customers + Wallet</h3>
  <p>Manage customer balance, deposit and discount.</p>

  <table class="table" style="margin-top:14px;">
    <thead>
      <tr>
        <th>#</th>
        <th>User</th>
        <th>Email</th>
        <th>Balance</th>
        <th>Total Deposit</th>
        <th>Used</th>
        <th>Discount</th>
        <th style="width:160px;">Action</th>
      </tr>
    </thead>

    <tbody>
      @forelse($users as $u)
        @php
          $w = $u->wallet;
        @endphp
        <tr>
          <td>{{ $loop->iteration + ($users->currentPage()-1)*$users->perPage() }}</td>

          <td style="font-weight:900;">{{ $u->name }}</td>
          <td style="opacity:.75;">{{ $u->email }}</td>

          <td>
            <span class="pill" style="background:#eef2ff;color:#1d4ed8;">
              ${{ number_format((float)($w->balance ?? 0), 2) }}
            </span>
          </td>

          <td>${{ number_format((float)($w->total_deposit ?? 0), 2) }}</td>
          <td>${{ number_format((float)($w->used_balance ?? 0), 2) }}</td>
          <td>{{ (int)($w->discount_percent ?? 0) }}%</td>

          <td>
            @if($w)
              <a class="btn-primary" href="{{ route('admin.wallets.edit', $w->id) }}" style="text-decoration:none;">
                <i class="fa-solid fa-wallet"></i> Topup
              </a>
            @else
              <span class="pill" style="background:#fef2f2;color:#b91c1c;">No wallet</span>
            @endif
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="8">
            <span class="pill">No customers found</span>
          </td>
        </tr>
      @endforelse
    </tbody>
  </table>

  <div style="margin-top:14px;">
    {{ $users->links() }}
  </div>
</div>

@endsection
