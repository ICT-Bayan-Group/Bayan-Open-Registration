<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pendaftaran Ditolak — Bayan Open 2026</title>
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
    color: #cc2200;
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

  /* Rejection reason box */
  .reason-block {
    border-left: 3px solid #cc2200;
    padding: 14px 18px;
    font-size: 13px;
    color: #333;
    line-height: 1.75;
    margin-bottom: 32px;
    background: #fdf5f5;
  }
  .reason-block .reason-label {
    font-size: 10px;
    font-weight: 600;
    letter-spacing: 2px;
    text-transform: uppercase;
    color: #cc2200;
    margin-bottom: 8px;
    font-family: 'IBM Plex Mono', monospace;
  }
  .reason-block p {
    color: #444;
    font-size: 13px;
    line-height: 1.75;
  }

  /* Notice */
  .notice {
    border-left: 3px solid #111;
    padding: 12px 16px;
    font-size: 12px;
    color: #555;
    line-height: 1.7;
    margin-bottom: 0;
  }
  .notice strong {
    color: #111;
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
      <p>Official Registration Notification</p>
    </div>
  </div>

  <!-- Status -->
  <div class="status-bar">Pendaftaran Ditolak</div>

  <!-- Body -->
  <div class="body">

    <p class="greeting">Halo, {{ $registration->nama }}</p>
    <p class="body-text">
      Mohon maaf, pendaftaran tim <strong>{{ $registration->tim_pb }}</strong> untuk
      <strong>Bayan Open 2026</strong> tidak dapat kami terima setelah melalui proses verifikasi.
      Berikut alasan penolakan dari panitia:
    </p>

    <!-- Alasan Penolakan -->
    <div class="reason-block">
      <div class="reason-label">Alasan Penolakan</div>
      <p>{{ $registration->rejection_reason ?? 'Tidak ada alasan yang diberikan.' }}</p>
    </div>

    <!-- Data Tim -->
    <div class="section-label">Data Tim</div>
    <table class="data-table">
      <tr>
        <td>Nama Ketua</td>
        <td>{{ $registration->nama }}</td>
      </tr>
      <tr>
        <td>Nama Tim</td>
        <td>{{ $registration->tim_pb }}</td>
      </tr>
      <tr>
        <td>Kategori</td>
        <td>{{ $registration->kategori_label }}</td>
      </tr>
      <tr>
        <td>Ditolak Pada</td>
        <td>{{ $registration->rejected_at?->format('d M Y, H:i') ?? now()->format('d M Y, H:i') }} WIB</td>
      </tr>
      <tr>
        <td>ID Pendaftaran</td>
        <td><span class="mono">{{ $registration->uuid }}</span></td>
      </tr>
    </table>

    <!-- Notice -->
    <div class="notice">
      Anda dapat <strong>mendaftar ulang</strong> dengan memastikan minimal 6 dari 8 anggota
      ber-KTP <strong>Kota Balikpapan</strong> dan foto KTP jelas terbaca.
      Jika ada pertanyaan, hubungi panitia di <strong>admin@bayanopen.com</strong>.
    </div>

  </div>

  <!-- Footer -->
  <div class="footer">
    Bayan Open 2026 &mdash; Official Badminton Tournament<br>
    Email ini dikirim otomatis, harap tidak membalas.<br>
    Pertanyaan? Hubungi kami di admin@bayanopen.com
  </div>

</div>
</body>
</html>