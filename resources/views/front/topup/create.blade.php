@extends('layouts.app')

@section('content')
<div class="wrap" style="max-width:520px;margin:30px auto;">
  @if (session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
  @endif

  <div class="box" style="padding:18px;border:1px solid #e5e7eb;border-radius:12px;">
    <h3 style="margin:0 0 10px;">Topup Wallet</h3>

    {{-- ✅ NEW: go to manual QR scan page first --}}
    <form method="GET" action="{{ route('topup.store') }}">
      <label style="font-weight:800;">Amount ($)</label>
      <input name="amount" type="number" min="5" class="form-control" required value="{{ old('amount') }}">

      @error('amount')
        <div class="text-danger mt-2">{{ $message }}</div>
      @enderror

      <button class="btn btn-primary mt-3" type="submit">
        Paynow
      </button>
    </form>
  </div>
</div>
@endsection
