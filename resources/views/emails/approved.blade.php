<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pendaftaran Diterima — Bayan Open 2026</title>
<style>
  @import url('https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;600&family=IBM+Plex+Sans:wght@400;500;600&display=swap');

  * { margin: 0; padding: 0; box-sizing: border-box; }

  body {
    font-family: 'IBM Plex Sans', sans-serif;
    background: #f5f5f5;
    color: #111;
  }

  .wrapper {
    max-width: 560px;
    margin: 40px auto;
    background: #fff;
    border: 1px solid #ddd;
  }

  /* Header */
  .header {
    padding: 32px 40px 28px;
    border-bottom: 1px solid #ddd;
    display: flex;
    align-items: center;
    gap: 16px;
  }
  .header img {
    height: 48px;
    object-fit: contain;
  }
  .header-text h1 {
    font-size: 15px;
    font-weight: 600;
    color: #111;
    letter-spacing: 0.5px;
  }
  .header-text p {
    font-size: 11px;
    color: #999;
    margin-top: 2px;
    letter-spacing: 0.5px;
  }

  /* Status */
  .status-bar {
    padding: 14px 40px;
    border-bottom: 1px solid #ddd;
    font-size: 12px;
    font-weight: 600;
    color: #111;
    letter-spacing: 1px;
    text-transform: uppercase;
    font-family: 'IBM Plex Mono', monospace;
  }
  .status-bar::before {
    content: '● ';
    color: #111;
  }

  /* Body */
  .body {
    padding: 32px 40px;
  }

  .greeting {
    font-size: 15px;
    font-weight: 600;
    color: #111;
    margin-bottom: 10px;
  }

  .body-text {
    font-size: 13px;
    color: #555;
    line-height: 1.75;
    margin-bottom: 32px;
  }

  /* Section label */
  .section-label {
    font-size: 10px;
    font-weight: 600;
    letter-spacing: 2px;
    text-transform: uppercase;
    color: #999;
    margin-bottom: 12px;
    font-family: 'IBM Plex Mono', monospace;
  }

  /* Data rows */
  .data-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 32px;
  }
  .data-table tr {
    border-bottom: 1px solid #eee;
  }
  .data-table tr:last-child {
    border-bottom: none;
  }
  .data-table td {
    padding: 10px 0;
    font-size: 13px;
    vertical-align: top;
  }
  .data-table td:first-child {
    color: #999;
    width: 45%;
  }
  .data-table td:last-child {
    color: #111;
    font-weight: 500;
    text-align: right;
  }
  .mono {
    font-family: 'IBM Plex Mono', monospace;
    font-size: 12px;
    color: #111;
  }

  /* Players */
  .players-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 32px;
  }
  .players-table tr {
    border-bottom: 1px solid #eee;
  }
  .players-table tr:last-child {
    border-bottom: none;
  }
  .players-table td {
    padding: 10px 0;
    font-size: 13px;
    vertical-align: top;
  }
  .players-table td:first-child {
    color: #999;
    font-family: 'IBM Plex Mono', monospace;
    font-size: 12px;
    width: 40px;
  }
  .players-table td:last-child {
    color: #111;
    font-weight: 500;
  }

  /* Divider */
  .divider {
    border: none;
    border-top: 1px solid #ddd;
    margin: 0 0 32px;
  }

  /* Total */
  .total-row {
    display: flex;
    justify-content: space-between;
    align-items: baseline;
    padding: 16px 0;
    border-top: 2px solid #111;
    border-bottom: 2px solid #111;
    margin-bottom: 32px;
  }
  .total-row .label {
    font-size: 12px;
    font-weight: 600;
    letter-spacing: 1px;
    text-transform: uppercase;
    color: #111;
    font-family: 'IBM Plex Mono', monospace;
  }
  .total-row .amount {
    font-size: 22px;
    font-weight: 600;
    color: #111;
    font-family: 'IBM Plex Mono', monospace;
  }

  /* Notice */
  .notice {
    border-left: 3px solid #111;
    padding: 12px 16px;
    font-size: 12px;
    color: #555;
    line-height: 1.7;
    margin-bottom: 32px;
  }
  .notice strong {
    color: #111;
  }

  /* Steps */
  .steps {
    margin-bottom: 32px;
  }
  .step-row {
    display: flex;
    align-items: flex-start;
    gap: 14px;
    padding: 10px 0;
    border-bottom: 1px solid #eee;
    font-size: 13px;
    color: #555;
    line-height: 1.6;
  }
  .step-row:last-child {
    border-bottom: none;
  }
  .step-num {
    font-family: 'IBM Plex Mono', monospace;
    font-size: 11px;
    font-weight: 600;
    color: #999;
    min-width: 20px;
    padding-top: 1px;
  }

  /* CTA */
  .cta-wrap {
    margin-bottom: 12px;
  }
  .cta-btn {
    display: block;
    background: #111;
    color: #fff;
    font-family: 'IBM Plex Mono', monospace;
    font-weight: 600;
    font-size: 13px;
    letter-spacing: 1px;
    text-decoration: none;
    padding: 16px 24px;
    text-align: center;
  }

  .link-note {
    font-size: 11px;
    color: #999;
    word-break: break-all;
    margin-bottom: 32px;
    font-family: 'IBM Plex Mono', monospace;
    line-height: 1.6;
  }
  .link-note a {
    color: #555;
    text-decoration: underline;
  }

  /* Info */
  .info {
    font-size: 12px;
    color: #555;
    line-height: 1.7;
    padding: 14px 16px;
    border: 1px solid #ddd;
    margin-bottom: 0;
  }

  /* Footer */
  .footer {
    border-top: 1px solid #ddd;
    padding: 20px 40px;
    font-size: 11px;
    color: #aaa;
    line-height: 1.8;
    text-align: center;
    font-family: 'IBM Plex Mono', monospace;
  }
</style>
</head>
<body>
<div class="wrapper">

  <!-- Header -->
  <div class="header">
    <img src="https://res.cloudinary.com/djs5pi7ev/image/upload/q_auto/f_auto/v1775803080/bayanopen-logo_mfcb55.png" alt="Bayan Open 2026">
    <div class="header-text">
      <h1>Bayan Open 2026</h1>
      <p>Official Registration Confirmation</p>
    </div>
  </div>

  <!-- Status -->
  <div class="status-bar">Pendaftaran Diterima</div>

  <!-- Body -->
  <div class="body">

    <p class="greeting">Halo, {{ $registration->nama }}</p>
    <p class="body-text">
      Pendaftaran tim <strong>{{ $registration->tim_pb }}</strong> untuk kategori
      <strong>{{ $registration->kategori_label }}</strong> telah berhasil diterima.
      Silakan segera selesaikan pembayaran menggunakan link di bawah ini untuk mengonfirmasi keikutsertaan Anda.
    </p>

    <!-- Data Tim -->
    <div class="section-label">Data Pendaftaran</div>
    <table class="data-table">
      <tr>
        <td>Nama Ketua Tim</td>
        <td>{{ $registration->nama }}</td>
      </tr>
      <tr>
        <td>Tim / PB</td>
        <td>{{ $registration->tim_pb }}</td>
      </tr>
      <tr>
        <td>Kategori</td>
        <td>{{ $registration->kategori_label }}</td>
      </tr>
      <tr>
        <td>Jumlah Pemain</td>
        <td>{{ count($registration->pemain ?? []) }} orang</td>
      </tr>
      <tr>
        <td>Order ID</td>
        <td><span class="mono">{{ $registration->uuid }}</span></td>
      </tr>
      <tr>
        <td>Status</td>
        <td>DITERIMA</td>
      </tr>
    </table>

    <!-- Total -->
    <div class="total-row">
      <span class="label">Total Tagihan</span>
      <span class="amount">{{ $registration->harga_formatted }}</span>
    </div>

    <!-- Warning -->
    <div class="notice">
      <strong>Link pembayaran berlaku selama 24 jam</strong> hingga
      <strong>{{ $registration->payment_token_expires_at?->format('d M Y, H:i') ?? '-' }} WIB</strong>.
      Segera selesaikan pembayaran sebelum link kedaluwarsa. Jika sudah kedaluwarsa, hubungi panitia di <strong>bayan.open@gmail.com</strong> untuk meminta link baru.
    </div>

    <!-- Steps -->
    <div class="section-label">Langkah Selanjutnya</div>
    <div class="steps">
      <div class="step-row">
        <span class="step-num">01</span>
        <span>Klik tombol <strong>Bayar Sekarang</strong> di bawah</span>
      </div>
      <div class="step-row">
        <span class="step-num">02</span>
        <span>Pilih metode pembayaran (Transfer Bank, QRIS, dll.)</span>
      </div>
      <div class="step-row">
        <span class="step-num">03</span>
        <span>Selesaikan pembayaran sebesar <strong>{{ $registration->harga_formatted }}</strong></span>
      </div>
      <div class="step-row">
        <span class="step-num">04</span>
        <span>Receipt PDF akan dikirim otomatis ke email ini setelah pembayaran berhasil</span>
      </div>
    </div>

    <!-- CTA -->
    <div class="cta-wrap">
      <a href="{{ url('/payment/' . $registration->payment_token) }}" class="cta-btn">
        BAYAR SEKARANG
      </a>
    </div>
    <p class="link-note">
      Atau salin link berikut:<br>
      <a href="{{ url('/payment/' . $registration->payment_token) }}">{{ url('/payment/' . $registration->payment_token) }}</a>
    </p>

    <!-- Info -->
    <div class="info">
      Setelah pembayaran berhasil, Anda akan menerima email konfirmasi beserta <strong>receipt PDF</strong> sebagai bukti pendaftaran resmi Anda di Bayan Open 2026.
    </div>

  </div>

  <!-- Footer -->
  <div class="footer">
    Bayan Open 2026 &mdash; Official Registration System<br>
    Pertanyaan? Hubungi kami di bayan.open@gmail.com<br>
    Email ini dikirim otomatis, harap tidak membalas.
  </div>

</div>
</body>
</html>