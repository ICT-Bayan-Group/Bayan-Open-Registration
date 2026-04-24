<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pembayaran Berhasil — Bayan Open 2026</title>
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

  /* Pemain list */
  .pemain-list {
    text-align: right;
  }
  .pemain-item {
    font-size: 13px;
    color: #111;
    padding: 1px 0;
  }
  .pemain-item::before {
    content: "↳ ";
    color: #999;
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

  /* CTA */
  .cta-wrap {
    margin-bottom: 0;
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
  <div class="status-bar">Pembayaran Berhasil — Lunas</div>

  <!-- Body -->
  <div class="body">

    <p class="greeting">Halo, {{ $registration->nama }}</p>
    <p class="body-text">
      Pembayaran pendaftaran Anda untuk <strong>Bayan Open 2026</strong> telah berhasil dikonfirmasi.
      Receipt PDF terlampir pada email ini sebagai bukti pendaftaran resmi Anda. Simpan baik-baik.
    </p>

    <!-- Data Peserta -->
    <div class="section-label">Data Peserta</div>
    <table class="data-table">
      <tr>
        <td>Nama Ketua Tim</td>
        <td>{{ $registration->nama }}</td>
      </tr>
      <tr>
        <td>Kategori</td>
        <td>{{ $registration->kategori_label }}</td>
      </tr>
      <tr>
        <td>Provinsi</td>
        <td>{{ $registration->provinsi }}</td>
      </tr>
      @if($registration->pemain && count($registration->pemain) > 0)
      <tr>
        <td style="vertical-align:top;">Pemain ({{ $registration->jumlah_pemain }})</td>
        <td>
          <div class="pemain-list">
            @foreach($registration->pemain as $pemain)
            <div class="pemain-item">{{ $pemain }}</div>
            @endforeach
          </div>
        </td>
      </tr>
      @endif
    </table>

    <!-- Data Transaksi -->
    <div class="section-label">Informasi Transaksi</div>
    <table class="data-table">
      <tr>
        <td>Order ID</td>
        <td><span class="mono">{{ $registration->uuid }}</span></td>
      </tr>
      @if($registration->payment_verified_at)
      <tr>
        <td>Diverifikasi Pada</td>
        <td>{{ $registration->payment_verified_at->format('d M Y, H:i') }}</td>
      </tr>
      @endif
      <tr>
        <td>Status</td>
        <td>PAID / LUNAS</td>
      </tr>
      <tr>
        <td>Waktu Verifikasi</td>
        <td>{{ ($registration->payment_verified_at ?? $registration->updated_at)->format('d M Y, H:i') }} WIB</td>
      </tr>
    </table>

    <!-- Total -->
    <div class="total-row">
      <span class="label">Total Pembayaran</span>
      <span class="amount">Rp {{ number_format($registration->harga, 0, ',', '.') }}</span>
    </div>

    <!-- Notice -->
    <div class="notice">
      <strong>Receipt PDF</strong> terlampir pada email ini. Harap simpan sebagai bukti pendaftaran resmi untuk ditunjukkan saat hari pelaksanaan turnamen.
    </div>

    <!-- CTA -->
    <div class="cta-wrap">
      <a href="{{ url('/registration/status/' . $registration->uuid) }}" class="cta-btn">
        CEK STATUS PENDAFTARAN →
      </a>
    </div>

  </div>

  <!-- Footer -->
  <div class="footer">
    Bayan Open 2026 &mdash; Official Badminton Tournament<br>
    Email ini dikirim otomatis, harap tidak membalas.<br>
    Pertanyaan? Hubungi kami di bayan.open@gmail.com
  </div>

</div>
</body>
</html>