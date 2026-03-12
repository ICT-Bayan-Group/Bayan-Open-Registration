<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pendaftaran Disetujui — Bayan Open 2026</title>
<style>
  * { margin: 0; padding: 0; box-sizing: border-box; }
  body { font-family: 'Segoe UI', Arial, sans-serif; background: #f1f5f9; color: #1e293b; }

  .wrapper { max-width: 600px; margin: 32px auto; background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,0.08); }

  /* Header */
  .header {
    background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 100%);
    padding: 36px 40px;
    text-align: center;
    border-bottom: 4px solid #f97316;
  }
  .header img { height: 60px; object-fit: contain; margin-bottom: 16px; }
  .header h1 { font-size: 22px; font-weight: 900; color: #fff; letter-spacing: 2px; }
  .header h1 span { color: #f97316; }
  .header p { font-size: 12px; color: rgba(255,255,255,0.5); margin-top: 6px; letter-spacing: 1px; text-transform: uppercase; }

  /* Badge */
  .badge-wrap { text-align: center; padding: 28px 40px 0; }
  .badge-approved {
    display: inline-block;
    background: #f97316;
    color: #fff;
    font-size: 13px;
    font-weight: 700;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    padding: 10px 28px;
    border-radius: 100px;
  }

  /* Body */
  .body { padding: 32px 40px; }

  .greeting { font-size: 16px; font-weight: 600; color: #1e293b; margin-bottom: 8px; }
  .body-text { font-size: 14px; color: #64748b; line-height: 1.7; margin-bottom: 24px; }

  /* Detail Card */
  .detail-card {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 20px 24px;
    margin-bottom: 24px;
  }
  .detail-title {
    font-size: 10px;
    font-weight: 700;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: 2px;
    border-bottom: 1px solid #e2e8f0;
    padding-bottom: 8px;
    margin-bottom: 14px;
  }
  .detail-row { display: flex; justify-content: space-between; padding: 5px 0; font-size: 13px; }
  .detail-label { color: #64748b; }
  .detail-value { font-weight: 600; color: #1e293b; text-align: right; max-width: 60%; }
  .detail-value.order-id { font-family: monospace; color: #f97316; font-size: 12px; }
  .detail-value.approved { color: #f97316; }

  /* Total */
  .total-box {
    background: linear-gradient(135deg, #0f172a, #1e3a5f);
    border-radius: 12px;
    padding: 20px 24px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
  }
  .total-label { font-size: 13px; color: rgba(255,255,255,0.6); }
  .total-amount { font-size: 24px; font-weight: 900; color: #f97316; }

  /* Warning box */
  .warning-box {
    background: #fffbeb;
    border: 1px solid #fde68a;
    border-radius: 10px;
    padding: 14px 18px;
    font-size: 13px;
    color: #92400e;
    margin-bottom: 24px;
    line-height: 1.6;
  }

  /* Info box */
  .info-box {
    background: #f0fdf4;
    border: 1px solid #bbf7d0;
    border-radius: 10px;
    padding: 14px 18px;
    font-size: 13px;
    color: #166534;
    margin-bottom: 24px;
    line-height: 1.6;
  }

  /* CTA Button */
  .cta-wrap { text-align: center; margin-bottom: 16px; }
  .cta-btn {
    display: inline-block;
    background: linear-gradient(135deg, #f97316, #ea580c);
    color: #fff;
    font-weight: 700;
    font-size: 15px;
    letter-spacing: 1px;
    text-decoration: none;
    padding: 16px 40px;
    border-radius: 12px;
    box-shadow: 0 8px 24px rgba(249,115,22,0.35);
  }
  .link-note {
    text-align: center;
    font-size: 11px;
    color: #94a3b8;
    word-break: break-all;
    margin-bottom: 24px;
  }
  .link-note a { color: #f97316; }

  /* Footer */
  .footer {
    background: #f8fafc;
    border-top: 1px solid #e2e8f0;
    padding: 24px 40px;
    text-align: center;
    font-size: 11px;
    color: #94a3b8;
    line-height: 1.8;
  }
  .footer strong { color: #64748b; }
</style>
</head>
<body>
<div class="wrapper">

  <!-- Header -->
  <div class="header">
    <img src="https://res.cloudinary.com/djs5pi7ev/image/upload/v1773109896/LOGO_BO2026_pzbvxh.png" alt="Bayan Open 2026">
    <h1>BAYAN <span>OPEN</span> 2026</h1>
    <p>Official Registration Confirmation</p>
  </div>

  <!-- Badge -->
  <div class="badge-wrap">
    <div class="badge-approved">✅ Pendaftaran Disetujui</div>
  </div>

  <!-- Body -->
  <div class="body">

    <p class="greeting">Halo, {{ $registration->nama }}! 👋</p>
    <p class="body-text">
      Selamat! Tim <strong>{{ $registration->tim_pb }}</strong> telah <strong>disetujui</strong> oleh panitia <strong>Bayan Open 2026</strong>.
      Silakan segera lanjutkan ke pembayaran untuk mengonfirmasi pendaftaran Anda sebelum link kedaluwarsa.
    </p>

    <!-- Data Tim -->
    <div class="detail-card">
      <div class="detail-title">Data Tim</div>
      <div class="detail-row">
        <span class="detail-label">Nama Ketua Tim</span>
        <span class="detail-value">{{ $registration->nama }}</span>
      </div>
      <div class="detail-row">
        <span class="detail-label">Tim / PB</span>
        <span class="detail-value">{{ $registration->tim_pb }}</span>
      </div>
      <div class="detail-row">
        <span class="detail-label">Kategori</span>
        <span class="detail-value">{{ $registration->kategori_label }}</span>
      </div>
      <div class="detail-row">
        <span class="detail-label">Jumlah Anggota</span>
        <span class="detail-value">{{ $registration->jumlah_pemain }} orang</span>
      </div>
      <div class="detail-row">
        <span class="detail-label">Order ID</span>
        <span class="detail-value order-id">{{ $registration->midtrans_order_id }}</span>
      </div>
      <div class="detail-row">
        <span class="detail-label">Status</span>
        <span class="detail-value approved">✅ DISETUJUI</span>
      </div>
    </div>

    <!-- Total Tagihan -->
    <div class="total-box">
      <div>
        <div class="total-label">Total Tagihan</div>
        <div style="font-size:11px;color:rgba(255,255,255,0.4);margin-top:2px;">Kategori {{ $registration->kategori_label }}</div>
      </div>
      <div class="total-amount">{{ $registration->harga_formatted }}</div>
    </div>

    <!-- Expire Warning -->
    <div class="warning-box">
      ⚠️ <strong>Link pembayaran berlaku selama 3 hari</strong> hingga
      <strong>{{ $registration->payment_token_expires_at?->format('d M Y, H:i') ?? '-' }} WIB</strong>.
      Segera selesaikan pembayaran sebelum link kedaluwarsa.
      Jika sudah kedaluwarsa, hubungi panitia untuk meminta link baru.
    </div>

    <!-- CTA Button -->
    <div class="cta-wrap">
      <a href="{{ url('/payment/' . $registration->payment_token) }}" class="cta-btn">
        💳 Bayar Sekarang
      </a>
    </div>
    <p class="link-note">
      Atau copy link berikut:<br>
      <a href="{{ url('/payment/' . $registration->payment_token) }}">{{ url('/payment/' . $registration->payment_token) }}</a>
    </p>

    <!-- Info -->
    <div class="info-box">
      ℹ️ Setelah pembayaran berhasil, Anda akan menerima email konfirmasi beserta <strong>receipt PDF</strong> sebagai bukti pendaftaran resmi Anda.
    </div>

  </div>

  <!-- Footer -->
  <div class="footer">
    <p><strong>BAYAN OPEN 2026</strong> — Official Badminton Tournament</p>
    <p>Email ini dikirim secara otomatis, harap tidak membalas email ini.</p>
    <p style="margin-top:8px;">Pertanyaan? Hubungi kami di <strong>bayan.open@gmail.com</strong></p>
    <p style="margin-top:8px;">© 2026 Bayan Group. All rights reserved.</p>
  </div>

</div>
</body>
</html>