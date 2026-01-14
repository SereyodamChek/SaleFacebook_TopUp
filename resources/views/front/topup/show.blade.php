@extends('layouts.app')

@push('styles')
<style>
  .topup-wrap{ max-width: 900px; margin: 28px auto 40px; padding: 0 16px; }
  .topup-card{ background:#fff; border:1px solid #e5e7eb; border-radius:18px; box-shadow:0 20px 50px rgba(0,0,0,.08); overflow:hidden; }

  .topup-head{ padding:20px; border-bottom:1px solid #e5e7eb; display:flex; justify-content:space-between; gap:12px; }
  .topup-title{ margin:0; font-size:22px; font-weight:900; color:#111827; }
  .topup-sub{ margin-top:6px; color:#6b7280; font-weight:700; font-size:14px; }

  .topup-amount{
    padding:10px 14px;
    border-radius:14px;
    background:#eef2ff;
    color:#1d4ed8;
    font-weight:900;
    border:1px solid #dbeafe;
    font-size:16px;
  }

  .topup-body{ padding:20px; display:grid; grid-template-columns:1fr 320px; gap:18px; }

  .qr-box{
    background:#f9fafb;
    border:1px solid #e5e7eb;
    border-radius:18px;
    padding:22px;
    display:flex;
    flex-direction:column;
    align-items:center;
    justify-content:center;
    min-height:360px;
  }

  .qr-hint{
    margin-top:12px;
    color:#6b7280;
    font-weight:700;
    font-size:13px;
    text-align:center;
  }

  .side-box{
    background:#0f172a;
    color:#fff;
    border-radius:18px;
    padding:18px;
    box-shadow:0 18px 45px rgba(0,0,0,.18);
  }

  .status{
    margin-top:14px;
    padding:14px;
    border-radius:14px;
    background:rgba(255,255,255,.12);
    border:1px solid rgba(255,255,255,.18);
    font-weight:900;
    text-align:center;
    min-height:48px;
    transition:.2s ease;
  }

  .status.success{
    background:rgba(16,185,129,.25);
    border-color:rgba(16,185,129,.4);
    color:#ecfdf5;
  }

  .status.error{
    background:rgba(239,68,68,.25);
    border-color:rgba(239,68,68,.4);
  }

  .btn-verify{
    margin-top:14px;
    width:100%;
    padding:13px;
    border-radius:14px;
    border:none;
    background:#22c55e;
    color:#fff;
    font-weight:900;
    font-size:15px;
    cursor:pointer;
  }

  .btn-verify:disabled{
    opacity:.6;
    cursor:not-allowed;
  }

  .topup-foot{
    padding:16px;
    border-top:1px solid #e5e7eb;
    display:flex;
    justify-content:center;
  }

  .btn-back{
    padding:10px 16px;
    border-radius:14px;
    border:1px solid #e5e7eb;
    background:#fff;
    font-weight:900;
    text-decoration:none;
    color:#111827;
  }

  @media (max-width: 900px){
    .topup-body{ grid-template-columns:1fr; }
  }
</style>
@endpush

@section('content')
<div class="topup-wrap">
  <div class="topup-card">

    {{-- Header --}}
    <div class="topup-head">
      <div>
        <h1 class="topup-title">Scan KHQR to Topup</h1>
        <div class="topup-sub">
          សូមស្កេន QR ដោយ Bakong ឬ App ដែលគាំទ្រ KHQR
        </div>
      </div>

      <div class="topup-amount">
        {{ number_format($topup->amount) }} {{ $topup->currency }}
      </div>
    </div>

    {{-- Body --}}
    <div class="topup-body">

      {{-- QR --}}
      <div class="qr-box">
@if ($topup->qr)
    {!! QrCode::size(260)->generate($topup->qr) !!}
@else
    <div>❌ មិនអាចបង្កើត KHQR បាន</div>
@endif
      </div>

      {{-- Status --}}
      <div class="side-box">
        <div style="font-weight:900;">📌 Payment Status</div>

        <div id="statusBox" class="status">
          ⏳ កំពុងរង់ចាំការទូទាត់...
        </div>

        <button id="verifyBtn" class="btn-verify">
          🔍 Verify Payment
        </button>

        <div style="margin-top:10px; font-size:13px; color:rgba(255,255,255,.75);">
          ប្រព័ន្ធនឹងពិនិត្យដោយស្វ័យប្រវត្តិ
        </div>
      </div>

    </div>

    {{-- Footer --}}
    <div class="topup-foot">
      <a href="{{ route('topup.create') }}" class="btn-back">
        ← ប្ដូរចំនួនទឹកប្រាក់
      </a>
    </div>

  </div>
</div>

<script>
const verifyUrl = @json(route('topup.verify', $topup->id));
const statusBox = document.getElementById('statusBox');
const btn = document.getElementById('verifyBtn');
let paid = false;

async function verifyPayment(manual = false){
  if (paid) return;

  if (manual) {
    btn.disabled = true;
    statusBox.textContent = "🔄 កំពុងពិនិត្យការទូទាត់...";
  }

  try {
    const res = await fetch(verifyUrl + '?t=' + Date.now(), {
      headers: { 'Accept': 'application/json' }
    });

    const data = await res.json();
    const code = parseInt(data.responseCode ?? 999);

    if (code === 0) {
      paid = true;
      statusBox.textContent = "✅ Topup ជោគជ័យ! Wallet ត្រូវបានអាប់ដេត";
      statusBox.classList.add('success');
      btn.style.display = "none";

      setTimeout(() => {
        window.location.href = @json(route('store.index'));
      }, 1500);
    } else if (manual) {
      statusBox.textContent = "❌ មិនទាន់មានការទូទាត់";
      statusBox.classList.add('error');
      btn.disabled = false;
    }
  } catch (e) {
    if (manual) {
      statusBox.textContent = "❌ Error ពេលពិនិត្យ";
      btn.disabled = false;
    }
  }
}

/* auto polling every 5s */
setInterval(() => verifyPayment(false), 5000);

/* manual verify */
btn.addEventListener('click', () => verifyPayment(true));
</script>
@endsection
