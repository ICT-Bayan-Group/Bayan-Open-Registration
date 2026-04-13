<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Revisi Diperlukan — Bayan Open 2026</title>
<style>
  * { box-sizing: border-box; }
  body        { margin:0; padding:0; background:#0d0d0d; font-family:'Segoe UI',Arial,sans-serif; -webkit-font-smoothing:antialiased; }
  .wrapper    { max-width:600px; margin:0 auto; padding:32px 16px; }
  .card       { background:#111827; border-radius:20px; overflow:hidden; border:1px solid rgba(251,191,36,.15); }

  /* Header */
  .header     { background:linear-gradient(135deg,#d97706 0%,#92400e 100%); padding:40px 32px; text-align:center; position:relative; overflow:hidden; }
  .header::before { content:''; position:absolute; inset:0; background:url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E"); }
  .header-icon { width:72px; height:72px; background:rgba(255,255,255,.15); border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 20px; font-size:32px; position:relative; border:2px solid rgba(255,255,255,.25); }
  .header h1  { margin:0; color:#fff; font-size:22px; font-weight:800; letter-spacing:-.3px; position:relative; }
  .header p   { margin:8px 0 0; color:rgba(255,255,255,.7); font-size:13px; position:relative; }

  /* Badge */
  .status-badge { display:inline-flex; align-items:center; gap:6px; background:rgba(251,191,36,.15); border:1px solid rgba(251,191,36,.35); color:#fbbf24; border-radius:99px; padding:6px 16px; font-size:12px; font-weight:700; letter-spacing:.06em; text-transform:uppercase; margin:24px auto 0; }

  /* Body */
  .body       { padding:32px; }
  .greeting   { color:rgba(255,255,255,.8); font-size:15px; line-height:1.65; margin:0 0 24px; }

  /* Revision notes box */
  .notes-header { display:flex; align-items:center; gap:8px; margin-bottom:10px; }
  .notes-label  { font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.07em; color:rgba(255,255,255,.3); }
  .notes-box  { background:rgba(251,191,36,.05); border:1.5px solid rgba(251,191,36,.2); border-left:3px solid #f59e0b; border-radius:10px; padding:16px 20px; margin-bottom:24px; }
  .notes-box p { margin:0; font-size:13.5px; color:rgba(255,255,255,.75); line-height:1.75; white-space:pre-line; }

  /* Info tim */
  .info-box   { background:rgba(255,255,255,.03); border:1px solid rgba(255,255,255,.08); border-radius:12px; padding:20px 24px; margin-bottom:24px; }
  .info-grid  { display:grid; grid-template-columns:1fr 1fr; gap:14px; }
  .info-item  {}
  .info-label { font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:.07em; color:rgba(255,255,255,.25); margin-bottom:4px; }
  .info-value { font-size:13px; color:rgba(255,255,255,.75); font-weight:600; }
  .info-value.mono { font-family:monospace; letter-spacing:.04em; color:rgba(255,255,255,.6); }

  /* CTA Button */
  .cta-wrap   { text-align:center; margin:28px 0; }
  .cta-btn    { display:inline-block; background:linear-gradient(135deg,#f59e0b,#d97706); color:#fff; text-decoration:none; padding:16px 40px; border-radius:14px; font-size:15px; font-weight:800; letter-spacing:-.2px; box-shadow:0 8px 32px rgba(245,158,11,.25); }
  .cta-btn:hover { opacity:.9; }
  .cta-sub    { font-size:11px; color:rgba(255,255,255,.25); margin-top:10px; }

  /* Token expiry */
  .expiry-box { background:rgba(239,68,68,.05); border:1px solid rgba(239,68,68,.15); border-radius:10px; padding:14px 18px; display:flex; align-items:center; gap:12px; margin-bottom:24px; }
  .expiry-icon { font-size:20px; flex-shrink:0; }
  .expiry-text { font-size:12px; color:rgba(255,255,255,.45); line-height:1.6; }
  .expiry-text strong { color:rgba(239,68,68,.8); }

  /* Steps */
  .steps-title { font-size:12px; font-weight:700; text-transform:uppercase; letter-spacing:.07em; color:rgba(255,255,255,.3); margin-bottom:14px; }
  .steps      { display:flex; flex-direction:column; gap:10px; margin-bottom:24px; }
  .step       { display:flex; align-items:flex-start; gap:12px; }
  .step-num   { width:24px; height:24px; background:rgba(245,158,11,.15); border:1px solid rgba(245,158,11,.3); border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:11px; font-weight:800; color:#f59e0b; flex-shrink:0; margin-top:1px; }
  .step-text  { font-size:13px; color:rgba(255,255,255,.55); line-height:1.6; }
  .step-text strong { color:rgba(255,255,255,.75); }

  /* Footer */
  .footer     { text-align:center; padding:24px 32px; border-top:1px solid rgba(255,255,255,.05); }
  .footer p   { margin:0; font-size:11px; color:rgba(255,255,255,.18); line-height:1.7; }

  /* Divider */
  .divider    { border:none; border-top:1px solid rgba(255,255,255,.06); margin:24px 0; }

  @media (max-width:480px) {
    .info-grid { grid-template-columns:1fr; }
    .header    { padding:28px 20px; }
    .body      { padding:24px 20px; }
  }
</style>
</head>
<body>
<div class="wrapper">
  <div class="card">

    {{-- ── HEADER ─────────────────────────────────────────────── --}}
    <div class="header">
      <div class="header-icon">✏️</div>
      <h1>Revisi Pendaftaran Diperlukan</h1>
      <p>Bayan Open 2026 · Kategori Beregu</p>
      <div>
        <span class="status-badge">⚠ Perlu Perbaikan Data</span>
      </div>
    </div>

    {{-- ── BODY ────────────────────────────────────────────────── --}}
    <div class="body">

      <p class="greeting">
        Halo, <strong style="color:#fff;">{{ $registration->nama }}</strong>! 👋<br><br>
        Terima kasih sudah mendaftar di Bayan Open 2026. Tim admin telah meninjau pendaftaran
        tim <strong style="color:#f59e0b;">{{ $registration->tim_pb }}</strong> dan
        menemukan beberapa hal yang perlu diperbaiki sebelum pendaftaran dapat diproses lebih lanjut.
      </p>

      {{-- Catatan Admin --}}
      <div>
        <div class="notes-header">
          <span class="notes-label">💬 Catatan dari Admin</span>
        </div>
        <div class="notes-box">
          <p>{{ $registration->revision_notes }}</p>
        </div>
      </div>

      {{-- Info Tim --}}
      <div class="info-box">
        <div class="info-grid">
          <div class="info-item">
            <div class="info-label">Nama Tim</div>
            <div class="info-value">{{ $registration->tim_pb }}</div>
          </div>
          <div class="info-item">
            <div class="info-label">Order ID</div>
            <div class="info-value mono">{{ $registration->midtrans_order_id }}</div>
          </div>
          <div class="info-item">
            <div class="info-label">Diminta Pada</div>
            <div class="info-value">{{ $registration->revision_requested_at?->format('d M Y, H:i') }} WIB</div>
          </div>
          <div class="info-item">
            <div class="info-label">Revisi ke-</div>
            <div class="info-value">#{{ $registration->revision_count }}</div>
          </div>
        </div>
      </div>

      {{-- Expiry Warning --}}
      <div class="expiry-box">
        <div class="expiry-icon">⏰</div>
        <div class="expiry-text">
          Link perbaikan ini aktif hingga
          <strong>{{ $expiresAt?->format('d M Y, H:i') }} WIB</strong>.
          Setelah kadaluarsa, hubungi panitia untuk mendapatkan link baru.
        </div>
      </div>

      {{-- CTA Button --}}
      <div class="cta-wrap">
        <a href="{{ $revisionUrl }}" class="cta-btn">Perbaiki Data Pendaftaran →</a>
        <p class="cta-sub">Klik tombol di atas atau salin link di bawah ini ke browser</p>
        <p style="font-size:10px;color:rgba(255,255,255,.2);word-break:break-all;margin-top:6px;">{{ $revisionUrl }}</p>
      </div>

      <hr class="divider">

      {{-- Steps --}}
      <p class="steps-title">📋 Cara Melakukan Revisi</p>
      <div class="steps">
        <div class="step">
          <div class="step-num">1</div>
          <div class="step-text">Klik tombol <strong>"Perbaiki Data Pendaftaran"</strong> di atas</div>
        </div>
        <div class="step">
          <div class="step-num">2</div>
          <div class="step-text">Baca catatan admin dan <strong>perbaiki bagian yang bermasalah</strong> (edit data atau upload ulang foto KTP)</div>
        </div>
        <div class="step">
          <div class="step-num">3</div>
          <div class="step-text">Klik <strong>"Kirim Perbaikan"</strong> — pendaftaran kembali ke antrian verifikasi admin</div>
        </div>
        <div class="step">
          <div class="step-num">4</div>
          <div class="step-text">Jika disetujui, <strong>link pembayaran akan dikirim</strong> ke email ini</div>
        </div>
      </div>

      <p style="color:rgba(255,255,255,.25);font-size:11px;line-height:1.7;margin:0;">
        Jika ada pertanyaan, balas email ini atau hubungi panitia Bayan Open 2026.
        Email ini dikirim otomatis — mohon tidak balas langsung ke alamat ini.
      </p>

    </div>

    {{-- ── FOOTER ──────────────────────────────────────────────── --}}
    <div class="footer">
      <p>
        🏸 &nbsp;© 2026 Bayan Open · Balikpapan, Kalimantan Timur<br>
        Dikirim otomatis oleh sistem pendaftaran Bayan Open 2026
      </p>
    </div>

  </div>
</div>
</body>
</html>