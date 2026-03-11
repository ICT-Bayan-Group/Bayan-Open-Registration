<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pendaftaran Disetujui — Bayan Open 2026</title>
<style>
  body        { margin:0; padding:0; background:#0a0a0a; font-family:'Segoe UI',Arial,sans-serif; }
  .wrapper    { max-width:600px; margin:0 auto; padding:32px 16px; }
  .card       { background:#111827; border-radius:16px; overflow:hidden;
                border:1px solid rgba(249,115,22,.2); }
  .header     { background:linear-gradient(135deg,#f97316,#c2410c);
                padding:36px 32px; text-align:center; }
  .header h1  { margin:0; color:#fff; font-size:24px; font-weight:800; letter-spacing:-.5px; }
  .header p   { margin:8px 0 0; color:rgba(255,255,255,.75); font-size:14px; }
  .body       { padding:32px; }
  .badge      { display:inline-block; background:rgba(52,211,153,.1);
                border:1px solid rgba(52,211,153,.3); color:#34d399;
                border-radius:99px; padding:4px 14px; font-size:12px;
                font-weight:700; letter-spacing:.05em; margin-bottom:20px; }
  .greeting   { color:rgba(255,255,255,.85); font-size:16px; line-height:1.6; margin:0 0 20px; }
  .info-box   { background:rgba(249,115,22,.06); border:1px solid rgba(249,115,22,.2);
                border-radius:12px; padding:20px 24px; margin:24px 0; }
  .info-row   { display:flex; justify-content:space-between; align-items:center;
                padding:6px 0; border-bottom:1px solid rgba(255,255,255,.05); }
  .info-row:last-child { border-bottom:none; padding-bottom:0; }
  .info-label { font-size:12px; font-weight:700; text-transform:uppercase;
                letter-spacing:.06em; color:rgba(255,255,255,.3); }
  .info-value { font-size:13px; font-weight:600; color:rgba(255,255,255,.85); text-align:right; }
  .info-value.hl { color:#f97316; font-size:16px; }
  .cta-wrap   { text-align:center; margin:32px 0; }
  .cta-btn    { display:inline-block; background:linear-gradient(135deg,#f97316,#c2410c);
                color:#fff; font-weight:800; font-size:15px; text-decoration:none;
                padding:16px 36px; border-radius:12px; letter-spacing:.03em;
                box-shadow:0 8px 24px rgba(249,115,22,.35); }
  .cta-btn:hover { opacity:.9; }
  .link-note  { margin:12px 0 0; font-size:12px; color:rgba(255,255,255,.25);
                word-break:break-all; }
  .expire-note{ background:rgba(234,179,8,.07); border:1px solid rgba(234,179,8,.2);
                border-radius:10px; padding:14px 18px; margin:20px 0;
                font-size:12px; color:rgba(234,179,8,.85); line-height:1.6; }
  .footer     { text-align:center; padding:24px 32px; border-top:1px solid rgba(255,255,255,.06); }
  .footer p   { margin:0; font-size:11px; color:rgba(255,255,255,.2); line-height:1.7; }
</style>
</head>
<body>
<div class="wrapper">
  <div class="card">

    {{-- HEADER --}}
    <div class="header">
      <h1>🏸 Bayan Open 2026</h1>
      <p>Pendaftaran Tim Beregu</p>
    </div>

    {{-- BODY --}}
    <div class="body">
      <div class="badge">✅ PENDAFTARAN DISETUJUI</div>

      <p class="greeting">
        Halo, <strong style="color:#fff;">{{ $registration->nama }}</strong>!<br>
        Selamat! Tim <strong style="color:#f97316;">{{ $registration->tim_pb }}</strong>
        telah <strong style="color:#34d399;">disetujui</strong> oleh panitia Bayan Open 2026.
        Silakan lanjutkan ke pembayaran untuk mengonfirmasi pendaftaran Anda.
      </p>

      {{-- Info Tim --}}
      <div class="info-box">
        <div class="info-row">
          <span class="info-label">Nama Tim</span>
          <span class="info-value">{{ $registration->tim_pb }}</span>
        </div>
        <div class="info-row">
          <span class="info-label">Kategori</span>
          <span class="info-value">{{ $registration->kategori_label }}</span>
        </div>
        <div class="info-row">
          <span class="info-label">Jumlah Anggota</span>
          <span class="info-value">{{ $registration->jumlah_pemain }} orang</span>
        </div>
        <div class="info-row">
          <span class="info-label">Order ID</span>
          <span class="info-value" style="font-family:monospace;">{{ $registration->midtrans_order_id }}</span>
        </div>
        <div class="info-row">
          <span class="info-label">Total Pembayaran</span>
          <span class="info-value hl">{{ $registration->harga_formatted }}</span>
        </div>
      </div>

      {{-- CTA --}}
      <div class="cta-wrap">
        <a href="{{ url('/payment/' . $registration->payment_token) }}" class="cta-btn">
          💳 BAYAR SEKARANG
        </a>
        <p class="link-note">
          Atau copy link: {{ url('/payment/' . $registration->payment_token) }}
        </p>
      </div>

      {{-- Expire warning --}}
      <div class="expire-note">
        ⚠️ <strong>Link pembayaran berlaku selama 3 hari</strong> hingga
        <strong>{{ $registration->payment_token_expires_at?->format('d M Y, H:i') ?? '-' }} WIB</strong>.
        Segera selesaikan pembayaran sebelum link kedaluwarsa.
        Jika sudah kedaluwarsa, hubungi panitia untuk meminta link baru.
      </div>

      <p style="color:rgba(255,255,255,.4);font-size:12px;line-height:1.7;margin:16px 0 0;">
        Email ini dikirim otomatis oleh sistem Bayan Open 2026.
        Jika ada pertanyaan, balas email ini atau hubungi panitia.
      </p>
    </div>

    {{-- FOOTER --}}
    <div class="footer">
      <p>
        © 2026 Bayan Open · Samarinda, Kalimantan Timur<br>
        Diproses oleh sistem pendaftaran otomatis Bayan Open 2026
      </p>
    </div>

  </div>
</div>
</body>
</html>