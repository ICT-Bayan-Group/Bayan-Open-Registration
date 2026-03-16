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
            padding: 0;
        }

        /* Header */
        .header {
            background: #ffffff;
            padding: 28px 40px;
            border-bottom: 5px solid #6b7280;
            border-top: 5px solid #9ca3af;
        }

        .header-inner {
            display: table;
            width: 100%;
        }

        .header-left {
            display: table-cell;
            vertical-align: middle;
        }

        .header-right {
            display: table-cell;
            vertical-align: middle;
            text-align: right;
        }

        .logo-img {
            height: 55px;
            width: auto;
        }

        .receipt-title {
            font-size: 18px;
            font-weight: 900;
            color: #374151;
            letter-spacing: 3px;
            text-transform: uppercase;
        }

        .receipt-subtitle {
            font-size: 10px;
            color: #9ca3af;
            margin-top: 4px;
            letter-spacing: 1px;
        }

        /* Status */
        .status-wrap {
            background: #ffffff;
            padding: 12px 40px 20px;
            border-bottom: 3px solid #d1d5db;
        }

        .status-badge {
            display: inline-block;
            background: #4b5563;
            color: white;
            padding: 6px 18px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1px;
        }

        /* Body */
        .body {
            padding: 28px 40px;
        }

        /* Section */
        .section { margin-bottom: 20px; }

        .section-title {
            font-size: 9px;
            font-weight: 700;
            color: #374151;
            text-transform: uppercase;
            letter-spacing: 2px;
            border-bottom: 2px solid #9ca3af;
            padding-bottom: 5px;
            margin-bottom: 12px;
        }

        /* Table layout for info rows */
        .info-table { width: 100%; border-collapse: collapse; }
        .info-table td { padding: 5px 0; font-size: 11px; vertical-align: top; border-bottom: 1px dashed #e5e7eb; }
        .info-table td:first-child { width: 42%; color: #6b7280; }
        .info-table td:last-child { color: #1e293b; font-weight: 600; }

        /* Kategori badge */
        .badge {
            display: inline-block;
            padding: 2px 10px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .badge-regu { background: #e5e7eb; color: #374151; }
        .badge-open { background: #f3f4f6; color: #4b5563; }

        /* Pemain */
        .pemain-item { font-size: 11px; color: #1e293b; padding: 1px 0; }

        /* Divider */
        .divider { border: none; border-top: 3px solid #d1d5db; margin: 16px 0; }

        /* Total box */
        .total-box {
            background: #f9fafb;
            border: 2px solid #d1d5db;
            border-left: 6px solid #6b7280;
            border-radius: 10px;
            padding: 16px 20px;
            margin: 20px 0;
        }

        .total-inner { display: table; width: 100%; }
        .total-left { display: table-cell; vertical-align: middle; }
        .total-right { display: table-cell; vertical-align: middle; text-align: right; }

        .total-label { font-size: 12px; color: #374151; font-weight: 700; }
        .total-sub { font-size: 10px; color: #9ca3af; margin-top: 3px; }
        .total-amount { font-size: 22px; font-weight: 900; color: #374151; }

        /* Footer */
        .footer {
            margin-top: 30px;
            padding: 16px 40px;
            border-top: 4px solid #6b7280;
            border-bottom: 3px solid #d1d5db;
            text-align: center;
            color: #6b7280;
            font-size: 10px;
            line-height: 1.8;
        }

        /* Watermark */
        .watermark {
            position: fixed;
            bottom: 80px;
            right: 30px;
            font-size: 90px;
            font-weight: 900;
            color: rgba(107,114,128,0.06);
            transform: rotate(-30deg);
            letter-spacing: -3px;
        }
    </style>
</head>
<body>
<div class="page">

    {{-- Header --}}
    <div class="header">
        <div class="header-inner">
            <div class="header-left">
                @php
                    $logoPath = public_path('images/bayanopen.png');
                    $logoBase64 = '';
                    if (file_exists($logoPath)) {
                        $logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));
                    }
                @endphp
                @if($logoBase64)
                    <img src="{{ $logoBase64 }}" alt="Bayan Open 2026" class="logo-img">
                @else
                    <div style="font-size:20px;font-weight:900;color:#374151;letter-spacing:2px;">BAYAN <span style="color:#6b7280;">OPEN</span> 2026</div>
                @endif
            </div>
            <div class="header-right">
                <div class="receipt-title">RECEIPT</div>
                <div class="receipt-subtitle">{{ $registration->midtrans_order_id }}</div>
            </div>
        </div>
    </div>

    {{-- Status --}}
    <div class="status-wrap">
        <span class="status-badge">✓ PAID — LUNAS</span>
    </div>

    {{-- Body --}}
    <div class="body">

        {{-- Data Peserta --}}
        <div class="section">
            <div class="section-title">Data Peserta</div>
            <table class="info-table">
                <tr>
                    <td>Nama Ketua Tim</td>
                    <td>{{ $registration->nama }}</td>
                </tr>
                <tr>
                    <td>Tim / PB</td>
                    <td>{{ $registration->tim_pb }}</td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td>{{ $registration->email }}</td>
                </tr>
                <tr>
                    <td>No. HP</td>
                    <td>{{ $registration->no_hp }}</td>
                </tr>
                <tr>
                    <td>Provinsi / Kota</td>
                    <td>{{ $registration->provinsi }}, {{ $registration->kota }}</td>
                </tr>
                <tr>
                    <td>Kategori</td>
                    <td>
                        <span class="badge badge-{{ $registration->kategori }}">
                            {{ $registration->kategori_label }}
                        </span>
                    </td>
                </tr>
                @if($registration->nama_pelatih)
                <tr>
                    <td>Pelatih</td>
                    <td>{{ $registration->nama_pelatih }}
                        @if($registration->no_hp_pelatih) ({{ $registration->no_hp_pelatih }})@endif
                    </td>
                </tr>
                @endif
            </table>
        </div>

        {{-- Daftar Pemain --}}
        @if($registration->pemain && count($registration->pemain) > 0)
        <div class="section">
            <div class="section-title">Daftar Pemain ({{ $registration->jumlah_pemain }} orang)</div>
            @foreach($registration->pemain as $i => $pemain)
            <div class="pemain-item">{{ $i + 1 }}. {{ $pemain }}</div>
            @endforeach
        </div>
        @endif

        <hr class="divider">

        {{-- Informasi Transaksi --}}
        <div class="section">
            <div class="section-title">Informasi Transaksi</div>
            <table class="info-table">
                <tr>
                    <td>Order ID</td>
                    <td>{{ $registration->midtrans_order_id }}</td>
                </tr>
                @if($registration->midtrans_transaction_id)
                <tr>
                    <td>Transaction ID</td>
                    <td>{{ $registration->midtrans_transaction_id }}</td>
                </tr>
                @endif
                @if($registration->payment_type)
                <tr>
                    <td>Metode Pembayaran</td>
                    <td>{{ strtoupper(str_replace('_', ' ', $registration->payment_type)) }}</td>
                </tr>
                @endif
                <tr>
                    <td>Waktu Pembayaran</td>
                    <td>{{ ($registration->payment_time ?? $registration->updated_at)->format('d F Y, H:i:s') }} WIB</td>
                </tr>
                <tr>
                    <td>Tanggal Daftar</td>
                    <td>{{ $registration->created_at->format('d F Y, H:i:s') }} WIB</td>
                </tr>
            </table>
        </div>

        {{-- Total --}}
        <div class="total-box">
            <div class="total-inner">
                <div class="total-left">
                    <div class="total-label">Total Pembayaran</div>
                    <div class="total-sub">Kategori {{ $registration->kategori_label }}</div>
                </div>
                <div class="total-right">
                    <div class="total-amount">Rp {{ number_format($registration->harga, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="footer">
            <div><strong style="color:#374151;">BAYAN OPEN 2026</strong> — Official Registration Receipt</div>
            <div>Dokumen ini merupakan bukti pembayaran resmi. Simpan sebagai tanda pendaftaran Anda.</div>
            <div style="margin-top:6px;">Dicetak pada {{ now()->format('d F Y, H:i') }} WIB</div>
        </div>

    </div>

    <div class="watermark">PAID</div>

</div>
</body>
</html>