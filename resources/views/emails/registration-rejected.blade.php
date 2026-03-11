<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pendaftaran Ditolak — Bayan Open 2026</title>
<style>
  body        { margin:0; padding:0; background:#0a0a0a; font-family:'Segoe UI',Arial,sans-serif; }
  .wrapper    { max-width:600px; margin:0 auto; padding:32px 16px; }
  .card       { background:#111827; border-radius:16px; overflow:hidden;
                border:1px solid rgba(239,68,68,.2); }
  .header     { background:linear-gradient(135deg,#ef4444,#991b1b);
                padding:36px 32px; text-align:center; }
  .header h1  { margin:0; color:#fff; font-size:24px; font-weight:800; }
  .header p   { margin:8px 0 0; color:rgba(255,255,255,.75); font-size:14px; }
  .body       { padding:32px; }
  .badge      { display:inline-block; background:rgba(248,113,113,.1);
                border:1px solid rgba(248,113,113,.3); color:#f87171;
                border-radius:99px; padding:4px 14px; font-size:12px;
                font-weight:700; letter-spacing:.05em; margin-bottom:20px; }
  .greeting   { color:rgba(255,255,255,.85); font-size:16px; line-height:1.6; margin:0 0 20px; }
  .info-box   { background:rgba(239,68,68,.05); border:1px solid rgba(239,68,68,.2);
                border-radius:12px; padding:20px 24px; margin:24px 0; }
  .info-label { font-size:11px; font-weight:700; text-transform:uppercase;
                letter-spacing:.06em; color:rgba(255,255,255,.3); margin-bottom:6px; }
  .info-value { font-size:14px; color:rgba(255,255,255,.8); line-height:1.6; }
  .reason-box { background:rgba(239,68,68,.08); border:1px solid rgba(239,68,68,.25);
                border-left:3px solid #ef4444; border-radius:8px;
                padding:16px 20px; margin:20px 0; }
  .reason-box p { margin:0; font-size:14px; color:rgba(255,255,255,.75); line-height:1.7; }
  .retry-box  { background:rgba(249,115,22,.06); border:1px solid rgba(249,115,22,.2);
                border-radius:12px; padding:20px 24px; margin:24px 0; }
  .retry-box h3{ margin:0 0 10px; font-size:14px; color:#f97316; }
  .retry-box p { margin:0; font-size:13px; color:rgba(255,255,255,.55); line-height:1.7; }
  .footer     { text-align:center; padding:24px 32px; border-top:1px solid rgba(255,255,255,.06); }
  .footer p   { margin:0; font-size:11px; color:rgba(255,255,255,.2); line-height:1.7; }
</style>
</head>
<body>
<div class="wrapper">
  <div class="card">

    <div class="header">
      <h1>🏸 Bayan Open 2026</h1>
      <p>Pendaftaran Tim Beregu</p>
    </div>

    <div class="body">
      <div class="badge">❌ PENDAFTARAN DITOLAK</div>

      <p class="greeting">
        Halo, <strong style="color:#fff;">{{ $registration->nama }}</strong>.<br>
        Mohon maaf, pendaftaran tim <strong style="color:#f97316;">{{ $registration->tim_pb }}</strong>
        untuk Bayan Open 2026 <strong style="color:#f87171;">tidak dapat kami terima</strong>
        saat ini karena alasan berikut:
      </p>

      {{-- Alasan penolakan --}}
      <div class="reason-box">
        <p>{{ $registration->rejection_reason ?? 'Tidak ada alasan yang diberikan.' }}</p>
      </div>

      {{-- Info Tim --}}
      <div class="info-box">
        <div style="margin-bottom:14px;">
          <div class="info-label">Nama Tim</div>
          <div class="info-value">{{ $registration->tim_pb }}</div>
        </div>
        <div style="margin-bottom:14px;">
          <div class="info-label">Order ID</div>
          <div class="info-value" style="font-family:monospace;">{{ $registration->midtrans_order_id }}</div>
        </div>
        <div>
          <div class="info-label">Ditolak Pada</div>
          <div class="info-value">{{ $registration->rejected_at?->format('d M Y, H:i') ?? now()->format('d M Y, H:i') }} WIB</div>
        </div>
      </div>

      {{-- Langkah selanjutnya --}}
      <div class="retry-box">
        <h3>💡 Apa yang bisa dilakukan?</h3>
        <p>
          Anda dapat mendaftar ulang dengan memastikan semua anggota tim ber-KTP
          <strong style="color:#f97316;">Kota Balikpapan</strong> (minimal 6 dari 8 anggota).
          Pastikan foto KTP jelas dan dapat dibaca sistem OCR kami.
          Jika ada pertanyaan, balas email ini atau hubungi panitia.
        </p>
      </div>

      <p style="color:rgba(255,255,255,.4);font-size:12px;line-height:1.7;margin:16px 0 0;">
        Email ini dikirim otomatis oleh sistem Bayan Open 2026.
      </p>
    </div>

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