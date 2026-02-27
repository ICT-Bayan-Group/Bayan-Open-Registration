<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { margin: 0; padding: 0; background: #f1f5f9; font-family: 'Segoe UI', Arial, sans-serif; }
        .wrapper { max-width: 560px; margin: 30px auto; background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 10px 40px rgba(0,0,0,0.08); }
        .header { background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 100%); padding: 40px 40px 32px; border-bottom: 4px solid #f97316; }
        .header h1 { color: white; font-size: 22px; font-weight: 900; letter-spacing: 2px; margin: 0 0 4px; }
        .header h1 span { color: #f97316; }
        .header p { color: rgba(255,255,255,0.5); font-size: 12px; margin: 0; letter-spacing: 1px; }
        .body { padding: 36px 40px; }
        .badge { display: inline-block; background: #d1fae5; color: #065f46; padding: 6px 16px; border-radius: 20px; font-size: 12px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 24px; }
        .greeting { font-size: 16px; color: #1e293b; margin-bottom: 8px; font-weight: 700; }
        .sub { font-size: 14px; color: #64748b; line-height: 1.6; margin-bottom: 28px; }
        .card { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 24px; margin-bottom: 24px; }
        .card-title { font-size: 10px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 16px; }
        .row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #f1f5f9; font-size: 13px; }
        .row:last-child { border-bottom: none; }
        .row .label { color: #64748b; }
        .row .value { color: #1e293b; font-weight: 600; }
        .total-row { background: #0f172a; border-radius: 10px; padding: 16px 20px; display: flex; justify-content: space-between; align-items: center; margin-top: 16px; }
        .total-row .label { color: rgba(255,255,255,0.6); font-size: 13px; }
        .total-row .amount { color: #10b981; font-size: 22px; font-weight: 900; }
        .note { font-size: 13px; color: #64748b; line-height: 1.6; background: #fffbeb; border: 1px solid #fde68a; border-radius: 10px; padding: 16px; margin-bottom: 24px; }
        .footer { background: #f8fafc; padding: 24px 40px; text-align: center; }
        .footer p { color: #94a3b8; font-size: 12px; line-height: 1.6; margin: 0; }
        .footer strong { color: #1e293b; }
    </style>
</head>
<body>
<div class="wrapper">

    <div class="header">
        <h1>BAYAN <span>OPEN</span> 2026</h1>
        <p>KONFIRMASI PEMBAYARAN RESMI</p>
    </div>

    <div class="body">
        <span class="badge">✓ Pembayaran Diterima</span>

        <p class="greeting">Halo, {{ $registration->nama }}!</p>
        <p class="sub">
            Selamat! Pembayaran pendaftaran Bayan Open 2026 Anda telah berhasil dikonfirmasi.
            Receipt resmi terlampir dalam email ini sebagai bukti pendaftaran Anda.
        </p>

        <div class="card">
            <div class="card-title">Detail Peserta</div>
            <div class="row">
                <span class="label">Nama Lengkap</span>
                <span class="value">{{ $registration->nama }}</span>
            </div>
            <div class="row">
                <span class="label">Tim / PB</span>
                <span class="value">{{ $registration->tim_pb }}</span>
            </div>
            <div class="row">
                <span class="label">Kategori</span>
                <span class="value">{{ $registration->kategori_label }}</span>
            </div>
            <div class="row">
                <span class="label">Order ID</span>
                <span class="value" style="font-family:monospace;font-size:12px;">{{ $registration->midtrans_order_id }}</span>
            </div>
            <div class="row">
                <span class="label">Waktu Daftar</span>
                <span class="value">{{ $registration->created_at->format('d M Y, H:i') }} WIB</span>
            </div>
        </div>

        <div class="total-row">
            <span class="label">Total Dibayar</span>
            <span class="amount">Rp {{ number_format($registration->harga, 0, ',', '.') }}</span>
        </div>

        <br>

        <div class="note">
            📎 <strong>Receipt PDF</strong> terlampir dalam email ini. Simpan sebagai bukti resmi pendaftaran Anda.
            Tunjukkan receipt ini pada saat check-in di lokasi pertandingan.
        </div>

        <p style="font-size:13px;color:#64748b;">
            Jika ada pertanyaan, silakan hubungi panitia Bayan Open 2026.
            Kami berharap Anda sukses dalam pertandingan! 🏸
        </p>
    </div>

    <div class="footer">
        <p><strong>Bayan Open 2026</strong><br>
        Email ini dikirim secara otomatis — harap jangan membalas email ini.<br>
        © 2026 Bayan Open. All rights reserved.</p>
    </div>

</div>
</body>
</html>