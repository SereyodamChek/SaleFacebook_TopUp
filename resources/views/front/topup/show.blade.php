@extends('layouts.app')

@push('styles')
<style>
  .topup-wrap{ max-width: 860px; margin: 26px auto 40px; padding: 0 16px; }
  .topup-card{ background:#fff; border:1px solid rgba(229,231,235,.9); border-radius:16px; box-shadow: 0 18px 45px rgba(0,0,0,.06); overflow:hidden; }
  .topup-head{ padding: 18px; border-bottom: 1px solid rgba(229,231,235,.9); display:flex; justify-content:space-between; gap:12px; }
  .topup-title{ margin:0; font-size:22px; font-weight:900; color:#111827; }
  .topup-sub{ margin-top:6px; color:#6b7280; font-weight:700; font-size:14px; }
  .topup-amount{ padding:10px 12px; border-radius:12px; background:#eef2ff; color:#1d4ed8; font-weight:900; border:1px solid #dbeafe; }
  .topup-body{ padding:18px; display:grid; grid-template-columns: 1fr 300px; gap:16px; }

  .qr-box{
    background:#f9fafb;
    border:1px solid rgba(229,231,235,.9);
    border-radius:16px;
    padding:18px;
    display:flex;
    flex-direction:column;
    align-items:center;
  }

  .qr-hint{ margin-top:10px; color:#6b7280; font-weight:700; font-size:13px; text-align:center; }

  .side-box{
    background:#111827;
    color:#fff;
    border-radius:16px;
    padding:16px;
    box-shadow:0 18px 45px rgba(0,0,0,.14);
  }

  .status{
    margin-top:12px;
    padding:12px;
    border-radius:12px;
    background:rgba(255,255,255,.12);
    border:1px solid rgba(255,255,255,.2);
    font-weight:900;
    text-align:center;
    min-height:44px;
  }

  .btn-verify{
    margin-top:14px;
    width:100%;
    padding:12px;
    border-radius:12px;
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

  .topup-foot{ padding:16px; border-top:1px solid rgba(229,231,235,.9); display:flex; justify-content:center; gap:10px; }

  .btn-back{
    padding:10px 14px;
    border-radius:12px;
    border:1px solid rgba(229,231,235,.9);
    background:#fff;
    font-weight:900;
    text-decoration:none;
    color:#111827;
  }

  @media (max-width: 860px){
    .topup-body{ grid-template-columns:1fr; }
  }
</style>
@endpush

@section('content')
<div class="topup-wrap">
  <div class="topup-card">

    <div class="topup-head">
      <div>
        <h1 class="topup-title">Scan KHQR to Topup</h1>
        <div class="topup-sub">
          សូមស្កេន QR ដោយ Bakong / App ដែលគាំទ្រ KHQR
        </div>
      </div>

      <div class="topup-amount">
        ${{ number_format((float)$topup->amount, 2) }}
      </div>
    </div>

    <div class="topup-body">

      {{-- QR --}}
      <div class="qr-box">
        @if ($topup->qr)
          {!! QrCode::size(260)->generate($topup->qr) !!}
          <div class="qr-hint">
            បន្ទាប់ពីបង់ប្រាក់ សូមចុចប៊ូតុង “ខ្ញុំបានបង់ប្រាក់រួច”
          </div>
        @else
          <div class="alert alert-danger">
            ❌ មិនអាចបង្កើត KHQR បាន
          </div>
        @endif
      </div>

      {{-- Status + Verify --}}
      <div class="side-box">
        <div style="font-weight:900; margin-bottom:6px;">📌 Payment Status</div>

        <div id="statusBox" class="status">
          កំពុងរង់ចាំការទូទាត់...
        </div>

        <button id="verifyBtn" class="btn-verify">
          ខ្ញុំបានបង់ប្រាក់រួច
        </button>

        <div style="margin-top:10px; font-size:13px; color:rgba(255,255,255,.8);">
          ⚠ ប្រសិនបើទូទាត់រួច សូមរង់ចាំបន្តិចមុនចុច Verify
        </div>
      </div>

    </div>

    <div class="topup-foot">
      <a href="{{ route('topup.create') }}" class="btn-back">← ប្ដូរចំនួនទឹកប្រាក់</a>
    </div>

  </div>
</div>

<script>
const btn = document.getElementById('verifyBtn');
const statusBox = document.getElementById('statusBox');

btn.addEventListener('click', async () => {
  btn.disabled = true;
  statusBox.textContent = "កំពុងពិនិត្យការទូទាត់...";

  try {
    const res = await fetch(@json(route('topup.verify', $topup->id)), {
      method: 'GET',
      headers: { 'Accept': 'application/json' }
    });

    const data = await res.json();
    const code = parseInt(data.responseCode ?? 999);

    if (code === 0) {
      statusBox.textContent = "✅ Topup ជោគជ័យ! Wallet ត្រូវបានអាប់ដេត";
      statusBox.style.background = "rgba(16,185,129,.25)";
      setTimeout(() => {
        window.location.href = @json(route('store.index'));
      }, 1200);
    } else {
      statusBox.textContent = "❌ មិនទាន់មានការទូទាត់ សូមព្យាយាមម្ដងទៀត";
      btn.disabled = false;
    }
  } catch (e) {
    statusBox.textContent = "❌ Error ពេលពិនិត្យការទូទាត់";
    btn.disabled = false;
  }
});
</script>
@endsection
