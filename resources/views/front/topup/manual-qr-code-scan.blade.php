@extends('layouts.app')

@push('styles')
<style>
  /* ===== Manual QR Scan Page (same style as Topup QR Page) ===== */
  .topup-wrap{ max-width: 860px; margin: 26px auto 40px; padding: 0 16px; }
  .topup-card{ background:#fff; border:1px solid rgba(229,231,235,.9); border-radius:16px; box-shadow: 0 18px 45px rgba(0,0,0,.06); overflow:hidden; }
  .topup-head{ padding: 18px 18px 14px; border-bottom: 1px solid rgba(229,231,235,.9); display:flex; align-items:flex-start; justify-content:space-between; gap:12px; }
  .topup-title{ margin:0; font-size:22px; font-weight:900; letter-spacing:.2px; color:#111827; }
  .topup-sub{ margin-top:6px; color:#6b7280; font-weight:700; font-size:14px; }
  .topup-amount{ display:inline-flex; align-items:center; gap:10px; padding:10px 12px; border-radius:12px; background:#eef2ff; color:#1d4ed8; font-weight:900; white-space:nowrap; border:1px solid #dbeafe; }
  .topup-body{ padding: 18px; display:grid; grid-template-columns: 1fr 300px; gap:16px; align-items:start; }

  .qr-box{
    background: #f9fafb;
    border: 1px solid rgba(229,231,235,.9);
    border-radius: 16px;
    padding: 18px;
    display:flex;
    flex-direction:column;
    align-items:center;
    justify-content:center;
    min-height: 360px;
  }

  .qr-hint{ margin-top: 10px; color:#6b7280; font-weight:700; font-size:13px; text-align:center; }

  .help-box{
    width:100%;
    max-width: 360px;
    margin-top: 12px;
    background:#fff;
    border:1px dashed rgba(209,213,219,.9);
    border-radius:12px;
    padding:12px;
    color:#374151;
    font-weight:700;
    font-size:13px;
    line-height:1.55;
  }

  .side-box{ background:#111827; color:#fff; border-radius: 16px; padding: 16px; box-shadow: 0 18px 45px rgba(0,0,0,.14); position: relative; overflow:hidden; }
  .side-box::before{ content:""; position:absolute; inset:-40px -60px auto auto; width:160px; height:160px; border-radius:999px; background: rgba(59,130,246,.22); }

  .timer-title{ font-weight:900; letter-spacing:.2px; margin:0 0 6px; display:flex; align-items:center; gap:10px; }
  .countdown{ font-size: 36px; font-weight: 1000; line-height: 1; margin: 8px 0 6px; }
  .expires{ margin:0; color: rgba(255,255,255,.8); font-weight:700; font-size:13px; }

  .status{
    margin-top: 12px;
    padding: 10px 12px;
    border-radius: 12px;
    background: rgba(255,255,255,.10);
    border:1px solid rgba(255,255,255,.14);
    font-weight: 900;
    min-height: 40px;
    display:flex;
    align-items:center;
    justify-content:center;
    text-align:center;
  }

  .topup-foot{
    padding: 16px 18px;
    border-top: 1px solid rgba(229,231,235,.9);
    display:flex;
    align-items:center;
    justify-content:center;
    gap:10px;
    flex-wrap:wrap;
  }

  .btn-back{
    display:inline-flex; align-items:center; justify-content:center; gap:10px;
    padding: 10px 14px; border-radius: 12px; border:1px solid rgba(229,231,235,.9);
    background:#fff; color:#111827; font-weight: 900; text-decoration:none;
    transition: transform .06s ease, box-shadow .06s ease;
  }
  .btn-back:hover{ transform: translateY(-1px); box-shadow: 0 10px 25px rgba(0,0,0,.08); }

  .btn-muted{
    display:inline-flex; align-items:center; justify-content:center; gap:10px;
    padding: 10px 14px; border-radius: 12px; border:1px solid rgba(229,231,235,.9);
    background:#f9fafb; color:#111827; font-weight: 900; text-decoration:none;
  }

  .btn-primary-like{
    display:inline-flex; align-items:center; justify-content:center; gap:10px;
    padding: 10px 14px; border-radius: 12px;
    border:1px solid rgba(37,99,235,.25);
    background:#2563eb;
    color:#fff;
    font-weight: 900;
    text-decoration:none;
    transition: transform .06s ease, box-shadow .06s ease;
  }
  .btn-primary-like:hover{ transform: translateY(-1px); box-shadow: 0 10px 25px rgba(37,99,235,.18); color:#fff; }

  @media (max-width: 860px){
    .topup-body{ grid-template-columns: 1fr; }
    .side-box{ order: -1; }
    .qr-box{ min-height: 320px; }
  }
</style>
@endpush

@section('content')
<div class="topup-wrap">
  <div class="topup-card">

    <div class="topup-head">
      <div>
        <h1 class="topup-title">Manual QR Code Scan</h1>
      </div>

      <div class="topup-amount">
        Amount: {{ number_format((float)$amount, 0) }} $
      </div>
    </div>

    <div class="topup-body">

      {{-- LEFT: Static QR Image --}}
      <div class="qr-box">
        {{-- ✅ Put your static QR image in: public/images/static-qr.png --}}
        <img
          src="{{ asset('images/photo_2025-12-22_20-18-21.jpg') }}"
          alt="Static QR Code"
          style="width:280px;height:280px;object-fit:contain;border-radius:12px;border:1px solid rgba(229,231,235,.9);background:#fff;padding:10px;"
        >

        <div class="qr-hint">Keep this page open while scanning.</div>

        <div class="help-box">
  <div style="font-weight:900;color:#111827;margin-bottom:6px;">How to scan</div>
  <div>1) Open your banking app</div>
  <div>2) Go to <b>Scan QR</b></div>
  <div>3) Scan the code above</div>
  <div>
    4) Share the payment bill to Telegram support:
    <a href="https://t.me/papaandworldwide" target="_blank" style="font-weight:900;color:#2563eb;text-decoration:none;">
      @papaandworldwide
    </a>
  </div>
</div>


        {{-- OPTIONAL: Keep your continue button if you still want --}}
        <form method="GET" action="{{ route('topup.store') }}" style="margin-top:14px; width:100%; max-width:360px;">
          <input type="hidden" name="amount" value="{{ $amount }}">

          @error('amount')
            <div class="text-danger mt-2">{{ $message }}</div>
          @enderror

        </form>
      </div>

      {{-- RIGHT: Timer + Auto Redirect --}}
      <div class="side-box">
        <div class="timer-title">⏳ Session timer</div>

        <div id="countdown" class="countdown">120</div>
        <p class="expires">
          Redirecting in <span id="seconds">120</span> seconds.
        </p>

        <div id="statusBox" class="status">Waiting for scan...</div>

        <div style="margin-top:12px; color: rgba(255,255,255,.8); font-weight:700; font-size:13px; line-height:1.5;">
          If you didn’t scan within 2 minutes, we’ll send you back to change the amount.
        </div>
      </div>

    </div>

    <div class="topup-foot">
      <a href="{{ route('topup.create') }}" class="btn-back">← Back to Topup</a>
      <a href="{{ route('topup.create') }}" class="btn-muted">Change Amount</a>
    </div>

  </div>
</div>

<script>
  let timeLeft = 120;
  const countdownElement = document.getElementById('countdown');
  const secondsText = document.getElementById('seconds');
  const statusBox = document.getElementById('statusBox');

  const timer = setInterval(() => {
    timeLeft--;
    countdownElement.textContent = timeLeft;
    secondsText.textContent = timeLeft;

    if (timeLeft > 0) {
      statusBox.textContent = "Waiting for scan...";
    }

    if (timeLeft <= 0) {
      clearInterval(timer);
      window.location.href = @json(route('topup.create'));
    }
  }, 1000);
</script>
@endsection
