<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Invoice #{{ $order->id }}</title>

  <style>
    /* ===============================
       DomPDF SAFE STYLES
    =============================== */
    @page { margin: 28px 34px; }
    body {
      font-family: DejaVu Sans, sans-serif;
      font-size: 12px;
      color: #111;
    }

    table { width: 100%; border-collapse: collapse; }

    .brand-blue { color: #1776c6; }
    .muted { color: #666; }
    .right { text-align: right; }
    .center { text-align: center; }

    /* ===============================
       HEADER
    =============================== */
    .header-table td { vertical-align: top; }

    .company-name {
      font-size: 22px;
      font-weight: 800;
      color: #1776c6;
      margin-bottom: 4px;
    }

    .company-info div {
      margin: 2px 0;
    }

    .invoice-box {
      text-align: right;
    }

    .invoice-title {
      font-size: 26px;
      font-weight: 800;
      letter-spacing: 1px;
    }

    .invoice-meta {
      margin-top: 6px;
      font-size: 12px;
    }

    .invoice-meta div {
      margin: 2px 0;
    }

    .divider {
      border-top: 2px solid #1776c6;
      margin: 18px 0 20px;
    }

    /* ===============================
       BILL TO + META
    =============================== */
    .info-table td { vertical-align: top; }

    .billto-title {
      font-size: 16px;
      font-weight: 800;
      color: #1776c6;
      margin-bottom: 6px;
    }

    /* ===============================
       ITEMS TABLE
    =============================== */
    .items {
      margin-top: 16px;
    }

    .items th {
      background: #1776c6;
      color: #fff;
      font-weight: 800;
      padding: 10px 8px;
      border: 1px solid #1776c6;
      text-align: left;
    }

    .items td {
      border: 1px solid #cfe3f4;
      padding: 9px 8px;
    }

    .items tbody tr:nth-child(even) td {
      background: #eaf3fb;
    }

    /* ===============================
       TOTALS
    =============================== */
    .bottom-table { margin-top: 18px; }
    .bottom-table td { vertical-align: top; }

    .pay-title {
      font-size: 15px;
      font-weight: 800;
      color: #1776c6;
      margin-bottom: 6px;
    }

    .totals-box td {
      padding: 6px 0;
      font-size: 13px;
    }

    .totals-box .label {
      text-align: right;
      padding-right: 12px;
      font-weight: 700;
    }

    .totals-box .value {
      text-align: right;
      font-weight: 800;
    }

    .totals-hr {
      border-top: 2px solid #1776c6;
      margin: 10px 0;
    }

    /* ===============================
       SIGNATURE
    =============================== */
    .signature-wrap {
      margin-top: 30px;
      text-align: right;
    }

    .sig-line {
      display: inline-block;
      width: 220px;
      border-top: 1px solid #222;
      margin-top: 34px;
    }

    .sig-text {
      margin-top: 6px;
      font-weight: 700;
    }
  </style>
</head>

<body>

  <!-- ===============================
       HEADER
  =============================== -->
  <table class="header-table">
    <tr>
      <td style="width: 60%;">
        <div class="company-name">
          {{ config('app.name', 'Your Company') }}
        </div>

        <div class="company-info muted">
          <div>67/h, Martin street</div>
          <div>Alexander road</div>
          <div>576832</div>
          <div>Mobile: +123456789</div>
          <div>Email: example@gmail.com</div>
        </div>
      </td>

      <td style="width: 40%;" class="invoice-box">
        <div class="invoice-title brand-blue">INVOICE</div>

        <div class="invoice-meta">
          <div><strong>No:</strong> INV-{{ str_pad($order->id, 3, '0', STR_PAD_LEFT) }}</div>
          <div><strong>Date:</strong> {{ $order->created_at->format('M d, Y') }}</div>
          <div><strong>Status:</strong> {{ ucfirst($order->status) }}</div>
        </div>
      </td>
    </tr>
  </table>

  <div class="divider"></div>

  <!-- ===============================
       BILL TO + META
  =============================== -->
  <table class="info-table">
    <tr>
      <td style="width: 58%;">
        <div class="billto-title">Bill To</div>
        <div><strong>{{ $order->user->name }}</strong></div>
        <div>{{ $order->billing_address_line1 ?? 'Street / Address line 1' }}</div>
        <div>{{ $order->billing_address_line2 ?? 'City / State' }}</div>
        <div>{{ $order->billing_country ?? 'Country' }}</div>
      </td>
    </tr>
  </table>

  <!-- ===============================
       ITEMS
  =============================== -->
  <table class="items">
    <thead>
      <tr>
        <th style="width:6%" class="center">#</th>
        <th>Description</th>
        <th style="width:10%" class="right">Qty</th>
        <th style="width:15%" class="right">Price</th>
        <th style="width:15%" class="right">Amount</th>
      </tr>
    </thead>
    <tbody>
      @foreach($order->items as $i => $item)
        @php
          $qty = (int) $item->qty;
          $price = (float) $item->price;
          $amount = $qty * $price;
        @endphp
        <tr>
          <td class="center">{{ $i + 1 }}</td>
          <td>{{ $item->product->title ?? 'Product' }}</td>
          <td class="right">{{ $qty }}</td>
          <td class="right">${{ number_format($price, 2) }}</td>
          <td class="right">${{ number_format($amount, 2) }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>

  <!-- ===============================
       TOTALS
  =============================== -->
  <table class="bottom-table">
    <tr>
      <td style="width: 55%;">
        <div class="pay-title">Payment Instructions</div>
        <div class="muted">Pay Cheque to</div>
        <div><strong>{{ config('app.name') }}</strong></div>
      </td>

      <td style="width: 45%;">
        <table class="totals-box">
          <tr>
            <td class="label">Subtotal</td>
            <td class="value">${{ number_format($subtotal, 2) }}</td>
          </tr>
        </table>

        <div class="totals-hr"></div>

        <table class="totals-box">
          <tr>
            <td class="label">Total</td>
            <td class="value">${{ number_format($total, 2) }}</td>
          </tr>
        </table>
      </td>
    </tr>
  </table>

  <!-- ===============================
       SIGNATURE
  =============================== -->
  <div class="signature-wrap">
    <div class="sig-line"></div>
    <div class="sig-text">Authorized Signatory</div>
  </div>

</body>
</html>
