<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            background: #ffffff;
            color: #1e293b;
            font-size: 12px;
        }

        .page {
            width: 100%;
            min-height: 297mm;
            padding: 40px;
        }

        /* Header */
        .header {
            background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 100%);
            color: white;
            padding: 32px 40px;
            margin: -40px -40px 32px -40px;
            border-bottom: 4px solid #f97316;
        }

        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .logo-area h1 {
            font-size: 22px;
            font-weight: 900;
            letter-spacing: 2px;
            color: #ffffff;
        }

        .logo-area h1 span { color: #f97316; }

        .logo-area p {
            font-size: 10px;
            color: rgba(255,255,255,0.6);
            margin-top: 4px;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .receipt-label {
            text-align: right;
        }

        .receipt-label .title {
            font-size: 16px;
            font-weight: 700;
            color: #f97316;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .receipt-label .subtitle {
            font-size: 10px;
            color: rgba(255,255,255,0.5);
            margin-top: 4px;
        }

        /* Status Badge */
        .status-badge {
            display: inline-block;
            background: #10b981;
            color: white;
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-top: 16px;
        }

        /* Section */
        .section {
            margin-bottom: 24px;
        }

        .section-title {
            font-size: 9px;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 2px;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 6px;
            margin-bottom: 14px;
        }

        /* Info Grid */
        .info-grid {
            display: table;
            width: 100%;
        }

        .info-row {
            display: table-row;
        }

        .info-label {
            display: table-cell;
            width: 40%;
            color: #64748b;
            padding: 5px 0;
            font-size: 11px;
        }

        .info-value {
            display: table-cell;
            color: #1e293b;
            padding: 5px 0;
            font-size: 11px;
            font-weight: 600;
        }

        /* Total Box */
        .total-box {
            background: #f8fafc;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 20px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 24px 0;
        }

        .total-label {
            font-size: 12px;
            color: #64748b;
            font-weight: 600;
        }

        .total-amount {
            font-size: 24px;
            font-weight: 900;
            color: #10b981;
            letter-spacing: -1px;
        }

        /* Divider */
        .divider {
            border: none;
            border-top: 1px dashed #e2e8f0;
            margin: 20px 0;
        }

        /* Footer */
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
            text-align: center;
            color: #94a3b8;
            font-size: 10px;
        }

        .footer p { margin-bottom: 4px; }

        .watermark {
            position: fixed;
            bottom: 60px;
            right: 40px;
            font-size: 80px;
            font-weight: 900;
            color: rgba(16,185,129,0.06);
            transform: rotate(-30deg);
            pointer-events: none;
            letter-spacing: -2px;
        }

        .kategori-badge {
            display: inline-block;
            padding: 3px 12px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .kategori-regu { background: #e0e7ff; color: #4338ca; }
        .kategori-open { background: #d1fae5; color: #065f46; }
    </style>
</head>
<body>
<div class="page">

    {{-- Header --}}
    <div class="header">
        <div class="header-top">
            <div class="logo-area">
                <h1>BAYAN <span>OPEN</span> 2026</h1>
                <p>Official Registration Receipt</p>
            </div>
            <div class="receipt-label">
                <div class="title">RECEIPT</div>
                <div class="subtitle">{{ $registration->midtrans_order_id }}</div>
            </div>
        </div>
        <div>
            <span class="status-badge">✓ PAID — LUNAS</span>
        </div>
    </div>

    {{-- Peserta Info --}}
    <div class="section">
        <div class="section-title">Data Peserta</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Nama Lengkap</div>
                <div class="info-value">{{ $registration->nama }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Tim / PB</div>
                <div class="info-value">{{ $registration->tim_pb }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Kategori</div>
                <div class="info-value">
                    <span class="kategori-badge kategori-{{ $registration->kategori }}">
                        {{ $registration->kategori_label }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <hr class="divider">

    {{-- Transaksi Info --}}
    <div class="section">
        <div class="section-title">Informasi Transaksi</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Order ID</div>
                <div class="info-value">{{ $registration->midtrans_order_id }}</div>
            </div>
            @if($registration->midtrans_transaction_id)
            <div class="info-row">
                <div class="info-label">Transaction ID</div>
                <div class="info-value">{{ $registration->midtrans_transaction_id }}</div>
            </div>
            @endif
            @if($registration->payment_type)
            <div class="info-row">
                <div class="info-label">Metode Pembayaran</div>
                <div class="info-value">{{ strtoupper(str_replace('_', ' ', $registration->payment_type)) }}</div>
            </div>
            @endif
            <div class="info-row">
                <div class="info-label">Waktu Pembayaran</div>
                <div class="info-value">
                    {{ $registration->payment_time ? $registration->payment_time->format('d F Y, H:i:s') : $registration->updated_at->format('d F Y, H:i:s') }} WIB
                </div>
            </div>
            <div class="info-row">
                <div class="info-label">Tanggal Daftar</div>
                <div class="info-value">{{ $registration->created_at->format('d F Y, H:i:s') }} WIB</div>
            </div>
        </div>
    </div>

    {{-- Total Box --}}
    <div class="total-box">
        <div>
            <div class="total-label">Total Pembayaran</div>
            <div style="font-size:10px;color:#94a3b8;margin-top:4px;">Termasuk biaya pendaftaran kategori {{ $registration->kategori_label }}</div>
        </div>
        <div class="total-amount">Rp {{ number_format($registration->harga, 0, ',', '.') }}</div>
    </div>

    {{-- Footer --}}
    <div class="footer">
        <p><strong>BAYAN OPEN 2026</strong> — Official Registration Receipt</p>
        <p>Dokumen ini merupakan bukti pembayaran resmi. Simpan sebagai tanda pendaftaran Anda.</p>
        <p style="margin-top:8px;">Dicetak pada {{ now()->format('d F Y, H:i') }} WIB</p>
    </div>

    <div class="watermark">PAID</div>

</div>
</body>
</html>