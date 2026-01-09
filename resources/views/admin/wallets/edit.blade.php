@extends('layouts.admin')

@section('page_title', 'Topup Wallet')

@section('top_actions')
  <a class="btn" href="{{ route('admin.wallets.index') }}" style="text-decoration:none;">← Back</a>
@endsection

@section('admin_content')

<div class="box" style="box-shadow:none; max-width:720px;">
  <h3>Topup: {{ $wallet->user->name }}</h3>
  <p style="opacity:.75;">{{ $wallet->user->email }}</p>

  <div style="display:flex; gap:10px; flex-wrap:wrap; margin:12px 0;">
    <span class="pill" style="background:#eef2ff;color:#1d4ed8;">
      Balance: ${{ number_format((float)$wallet->balance, 2) }}
    </span>
    <span class="pill">Deposit: ${{ number_format((float)$wallet->total_deposit, 2) }}</span>
    <span class="pill">Used: ${{ number_format((float)$wallet->used_balance, 2) }}</span>
    <span class="pill">Discount: {{ (int)$wallet->discount_percent }}%</span>
  </div>

  <form method="POST" action="{{ route('admin.wallets.update', $wallet->id) }}" style="margin-top:14px;">
    @csrf
    @method('PATCH')

    <div class="form-grid" style="grid-template-columns: 1fr 1fr;">
      <div class="field">
        <label class="label">Topup Amount ($)</label>
        <input type="number" step="0.01" min="0.01" name="amount" class="input" required>
        @error('amount') <div class="error">{{ $message }}</div> @enderror
      </div>

      <div class="field">
        <label class="label">Discount % (optional)</label>
        <input type="number" min="0" max="100" name="discount_percent" class="input" value="{{ (int)$wallet->discount_percent }}">
        @error('discount_percent') <div class="error">{{ $message }}</div> @enderror
      </div>
    </div>

    <div class="form-actions" style="margin-top:12px;">
      <button class="btn-primary" type="submit">
        <i class="fa-solid fa-wallet"></i> Apply Topup
      </button>
    </div>
  </form>
</div>

@endsection
