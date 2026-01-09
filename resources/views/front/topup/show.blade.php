@extends('layouts.app')

@push('styles')
<style>
  /* ===== Topup QR Page (clean + modern) ===== */
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
  .md5{ margin-top: 10px; font-size: 12px; font-weight:800; color:#6b7280; word-break: break-all; background:#fff; border:1px dashed rgba(209,213,219,.9); border-radius:12px; padding:10px 12px; width:100%; max-width: 360px; text-align:left; }

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

  .topup-foot{ padding: 16px 18px; border-top: 1px solid rgba(229,231,235,.9); display:flex; align-items:center; justify-content:center; gap:10px; flex-wrap:wrap; }

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
        <h1 class="topup-title">Scan KHQR to Topup</h1>
        <div class="topup-sub">Open Bakong / KHQR supported app and scan to pay.</div>
      </div>

      <div class="topup-amount">
        Amount: {{ number_format((float)$topup->amount, 0) }} {{ $topup->currency }}
      </div>
    </div>

    <div class="topup-body">

      <div class="qr-box">
        @if ($topup->qr)
          {!! QrCode::size(280)->generate($topup->qr) !!}

          <div class="qr-hint">Keep this page open until payment is confirmed.</div>

          <div class="md5">
            <div style="font-weight:900; color:#111827; margin-bottom:6px;">MD5</div>
            <div style="opacity:.85;">{{ $topup->md5 }}</div>
          </div>
        @else
          <div class="alert alert-danger" style="width:100%; margin:0;">
            ⚠ Failed to generate KHQR.
          </div>
        @endif
      </div>

      <div class="side-box">
        <div class="timer-title">⏳ Payment status</div>

        <div id="countdown" class="countdown">120</div>
        <p class="expires">
          Expires in <span id="seconds">120</span> seconds.
        </p>

        <div id="statusBox" class="status">Waiting for payment...</div>

        <div style="margin-top:12px; color: rgba(255,255,255,.8); font-weight:700; font-size:13px; line-height:1.5;">
          If you already paid but status doesn’t change, wait a few seconds.
          We auto-check the transaction.
        </div>
      </div>

    </div>

    <div class="topup-foot">
      <a href="{{ route('store.index') }}" class="btn-back">← Back to Store</a>
      <a href="{{ route('topup.create') }}" class="btn-muted">Change Amount</a>
    </div>

  </div>
</div>

<script>
  let timeLeft = 120;
  const countdownElement = document.getElementById('countdown');
  const secondsText = document.getElementById('seconds');
  const statusBox = document.getElementById('statusBox');

  const verifyUrl = @json(route('topup.verify', $topup->id));

  const timer = setInterval(() => {
    timeLeft--;
    countdownElement.textContent = timeLeft;
    secondsText.textContent = timeLeft;

    if (timeLeft > 0) {
      // ✅ FIX: GET (not POST) + cache-buster to avoid caching
      fetch(verifyUrl + '?t=' + Date.now(), {
        method: "GET",
        headers: { "Accept": "application/json" }
      })
      .then(r => r.json())
      .then(data => {
        const code = parseInt(data.responseCode ?? data?.data?.responseCode ?? 999);

        if (code === 0) {
          clearInterval(timer);
          statusBox.textContent = "✅ Topup successful! Wallet updated.";
          statusBox.style.background = "rgba(16,185,129,.18)";
          statusBox.style.borderColor = "rgba(16,185,129,.35)";
          setTimeout(() => window.location.href = @json(route('store.index')), 1200);
        } else {
          statusBox.textContent = "Waiting for payment...";
        }
      })
      .catch(() => {
        statusBox.textContent = "Checking payment...";
      });
    }

    if (timeLeft <= 0) {
      clearInterval(timer);
      window.location.href = @json(route('topup.create'));
    }
  }, 1000);
</script>
@endsection
