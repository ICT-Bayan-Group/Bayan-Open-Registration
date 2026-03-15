<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RegistrationResource\Pages;
use App\Models\Registration;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\HtmlString;

class RegistrationResource extends Resource
{
    protected static ?string $model           = Registration::class;
    protected static ?string $navigationIcon  = 'heroicon-o-user-group';
    protected static ?string $navigationLabel = 'Peserta';
    protected static ?string $modelLabel      = 'Peserta';
    protected static ?string $pluralModelLabel = 'Data Peserta';
    protected static ?int    $navigationSort  = 1;

    // ============================================================
    // FORM (Create / Edit)
    // ============================================================

    public static function form(Form $form): Form
    {
        return $form->schema([

            Forms\Components\Section::make('Data Tim & Kontak')
                ->schema([
                    Forms\Components\TextInput::make('nama')
                        ->label('Nama Ketua Tim / PIC')
                        ->required()->maxLength(100),

                    Forms\Components\TextInput::make('tim_pb')
                        ->label('Nama Tim / PB')
                        ->required()->maxLength(100),

                    Forms\Components\TextInput::make('email')
                        ->label('Email')
                        ->email()->required()->maxLength(150),

                    Forms\Components\TextInput::make('no_hp')
                        ->label('No. WhatsApp / HP')
                        ->required()->maxLength(20),

                    Forms\Components\TextInput::make('provinsi')
                        ->label('Provinsi')
                        ->required()->maxLength(100),

                    Forms\Components\TextInput::make('kota')
                        ->label('Kota / Kabupaten')
                        ->required()->maxLength(100),
                ])->columns(2),

            Forms\Components\Section::make('Data Pelatih')
                ->schema([
                    Forms\Components\TextInput::make('nama_pelatih')
                        ->label('Nama Pelatih')->maxLength(100),

                    Forms\Components\TextInput::make('no_hp_pelatih')
                        ->label('No. HP Pelatih')->maxLength(20),
                ])->columns(2)->collapsible(),

            Forms\Components\Section::make('Kategori & Pembayaran')
                ->schema([
                    Forms\Components\Select::make('kategori')
                        ->label('Kategori')
                        ->options([
                            'ganda-dewasa-putra'  => 'Ganda Dewasa Putra',
                            'ganda-dewasa-putri'  => 'Ganda Dewasa Putri',
                            'ganda-veteran-putra' => 'Ganda Veteran Putra',
                            'beregu'              => 'Beregu',
                        ])
                        ->required()->live()
                        ->afterStateUpdated(fn ($state, Forms\Set $set) =>
                            $set('harga', $state === 'beregu' ? 200000 : 150000)
                        ),

                    Forms\Components\TextInput::make('harga')
                        ->label('Harga')->numeric()->prefix('Rp')->readOnly(),

                    Forms\Components\Select::make('status')
                        ->label('Status Pembayaran')
                        ->options([
                            'pending' => 'Pending',
                            'paid'    => 'Paid',
                            'failed'  => 'Failed',
                            'expired' => 'Expired',
                        ])->required(),

                    Forms\Components\TextInput::make('midtrans_order_id')
                        ->label('Order ID')->readOnly(),

                    Forms\Components\TextInput::make('payment_type')
                        ->label('Metode Bayar')->readOnly(),

                    Forms\Components\DateTimePicker::make('payment_time')
                        ->label('Waktu Bayar')->readOnly(),
                ])->columns(2),
        ]);
    }

    // ============================================================
    // INFOLIST (View detail)
    // ============================================================

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([

            // ── Ringkasan ────────────────────────────────────────
            Infolists\Components\Section::make('Ringkasan Pendaftaran')
                ->schema([
                    Infolists\Components\TextEntry::make('midtrans_order_id')
                        ->label('Order ID')->copyable()->fontFamily('mono')->weight('bold'),

                    Infolists\Components\TextEntry::make('kategori')
                        ->label('Kategori')
                        ->formatStateUsing(fn ($state) => match ($state) {
                            'ganda-dewasa-putra'  => 'Ganda Dewasa Putra',
                            'ganda-dewasa-putri'  => 'Ganda Dewasa Putri',
                            'ganda-veteran-putra' => 'Ganda Veteran Putra',
                            'beregu'              => 'Beregu',
                            default               => ucfirst($state),
                        })
                        ->badge()
                        ->color(fn ($state) => match ($state) {
                            'ganda-veteran-putra' => 'warning',
                            'beregu'              => 'info',
                            default               => 'primary',
                        }),

                    Infolists\Components\TextEntry::make('status')
                        ->label('Status')
                        ->badge()
                        ->color(fn ($state) => match ($state) {
                            'paid'    => 'success',
                            'pending' => 'warning',
                            'failed'  => 'danger',
                            'expired' => 'gray',
                            default   => 'gray',
                        })
                        ->formatStateUsing(fn ($state) => strtoupper($state)),

                    Infolists\Components\TextEntry::make('harga')
                        ->label('Total Pembayaran')
                        ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.'))
                        ->weight('bold')->color('primary'),

                    Infolists\Components\TextEntry::make('payment_type')
                        ->label('Metode Bayar')->placeholder('-'),

                    Infolists\Components\TextEntry::make('payment_time')
                        ->label('Waktu Bayar')->dateTime('d M Y, H:i')->placeholder('-'),
                ])->columns(3),

            // ── Data Tim ─────────────────────────────────────────
            Infolists\Components\Section::make('Data Tim & Kontak')
                ->schema([
                    Infolists\Components\TextEntry::make('nama')
                        ->label('Nama Ketua / PIC')->weight('semibold'),
                    Infolists\Components\TextEntry::make('tim_pb')
                        ->label('Tim / PB')->weight('semibold'),
                    Infolists\Components\TextEntry::make('email')
                        ->label('Email')->copyable(),
                    Infolists\Components\TextEntry::make('no_hp')
                        ->label('No. WhatsApp / HP')->copyable(),
                    Infolists\Components\TextEntry::make('provinsi')
                        ->label('Provinsi'),
                    Infolists\Components\TextEntry::make('kota')
                        ->label('Kota / Kabupaten'),
                ])->columns(2)->collapsible(),

            // ── Data Pelatih ─────────────────────────────────────
            Infolists\Components\Section::make('Data Pelatih')
                ->schema([
                    Infolists\Components\TextEntry::make('nama_pelatih')
                        ->label('Nama Pelatih')->placeholder('—'),
                    Infolists\Components\TextEntry::make('no_hp_pelatih')
                        ->label('No. HP Pelatih')->placeholder('—'),
                ])->columns(2)->collapsible(),

            // ── Ringkasan Veteran (hanya muncul jika veteran) ────
            Infolists\Components\Section::make('Verifikasi Usia Veteran')
                ->schema([
                    Infolists\Components\TextEntry::make('veteran_summary_html')
                        ->label('')->html()->columnSpanFull()
                        ->state(fn (Registration $r) => new HtmlString(
                            self::buildVeteranSummaryHtml($r)
                        )),
                ])
                ->visible(fn (Registration $r) => $r->kategori === 'ganda-veteran-putra'),

            // ── Data Pemain & KTP ─────────────────────────────────
            Infolists\Components\Section::make('Data Pemain & KTP')
                ->schema([
                    Infolists\Components\TextEntry::make('ktp_html')
                        ->label('')->html()->columnSpanFull()
                        ->state(fn (Registration $r) => new HtmlString(
                            self::buildKtpHtml($r)
                        )),
                ]),
        ]);
    }

    // ============================================================
    // VETERAN SUMMARY HTML
    // ============================================================

    private static function buildVeteranSummaryHtml(Registration $record): string
    {
        $usia   = $record->usia_pemain ?? [];
        $pemain = $record->pemain     ?? [];

        $u0    = isset($usia[0]) ? (int) $usia[0] : null;
        $u1    = isset($usia[1]) ? (int) $usia[1] : null;
        $total = ($u0 !== null && $u1 !== null) ? ($u0 + $u1) : null;

        $v0       = $u0 !== null && $u0 >= 45;
        $v1       = $u1 !== null && $u1 >= 45;
        $totalOk  = $total !== null && $total >= 95;
        $allValid = $v0 && $v1 && $totalOk;

        $nama0 = htmlspecialchars($pemain[0] ?? 'Pemain 1');
        $nama1 = htmlspecialchars($pemain[1] ?? 'Pemain 2');

        $borderColor = $allValid ? '#86efac' : '#fca5a5';
        $bgColor     = $allValid ? '#f0fdf4'  : '#fef2f2';
        $titleColor  = $allValid ? '#15803d'  : '#dc2626';
        $titleText   = $allValid
            ? '✓ Kedua pemain memenuhi syarat veteran'
            : '✗ Terdapat pelanggaran syarat veteran';

        $rowHtml = function ($nama, $usia, $valid) {
            $warna = $valid ? '#15803d' : '#dc2626';
            $icon  = $valid ? '✓' : '✗';
            $keterangan = $usia !== null
                ? ($valid
                    ? $usia . ' tahun — Memenuhi syarat (≥ 45 thn)'
                    : $usia . ' tahun — Tidak memenuhi syarat (min. 45 thn)')
                : 'Data usia tidak tersedia';

            return '
            <div style="display:flex;align-items:center;justify-content:space-between;padding:8px 0;border-bottom:1px solid #e5e7eb;">
                <div style="display:flex;align-items:center;gap:8px;">
                    <span style="font-size:12px;font-weight:700;color:' . $warna . ';">' . $icon . '</span>
                    <span style="font-size:12px;color:#374151;">' . $nama . '</span>
                </div>
                <span style="font-size:12px;font-weight:700;color:' . $warna . ';">' . $keterangan . '</span>
            </div>';
        };

        $totalColor = $totalOk ? '#15803d' : '#dc2626';
        $totalIcon  = $totalOk ? '✓' : '✗';
        $totalText  = $total !== null
            ? ($totalOk
                ? $total . ' tahun — Memenuhi syarat (≥ 95 thn)'
                : $total . ' tahun — Tidak memenuhi syarat (min. 95 thn)')
            : 'Data tidak lengkap';

        $html = '
        <div style="background:' . $bgColor . ';border:1px solid ' . $borderColor . ';border-radius:12px;padding:16px;">

            <p style="font-size:12px;font-weight:700;margin-bottom:12px;color:' . $titleColor . ';">'
                . $titleText . '
            </p>

            ' . $rowHtml($nama0, $u0, $v0) . '
            ' . $rowHtml($nama1, $u1, $v1) . '

            <div style="display:flex;align-items:center;justify-content:space-between;padding-top:12px;margin-top:4px;">
                <span style="font-size:12px;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:0.05em;">Total Usia 2 Pemain</span>
                <span style="font-size:14px;font-weight:800;color:' . $totalColor . ';">
                    ' . $totalIcon . ' ' . $totalText . '
                </span>
            </div>
        </div>';

        return $html;
    }

    // ============================================================
    // KTP PER PEMAIN HTML
    // ============================================================

    private static function buildKtpHtml(Registration $record): string
    {
        $pemain    = $record->pemain     ?? [];
        $nik       = $record->nik        ?? [];
        $tglLahir  = $record->tgl_lahir  ?? [];
        $usia      = $record->usia_pemain ?? [];
        $ktpFiles  = $record->ktp_files  ?? [];
        $ktpData   = $record->ktp_data   ?? [];
        $isVeteran = $record->kategori === 'ganda-veteran-putra';

        if (empty($pemain)) {
            return '<p style="color:#6b7280;font-size:14px;">Belum ada data pemain.</p>';
        }

        $html = '<div style="display:grid;grid-template-columns:1fr;gap:24px;">';

        foreach ($pemain as $i => $nama) {
            $nilNik   = htmlspecialchars($nik[$i]      ?? '—');
            $nilTgl   = htmlspecialchars($tglLahir[$i] ?? '—');
            $nilUsia  = isset($usia[$i]) ? (int) $usia[$i] : null;
            $namaHtml = htmlspecialchars($nama);

            // ── Baris usia ──
            $usiaHtml = '';
            if ($nilUsia !== null) {
                if ($isVeteran) {
                    $validPerPemain = $nilUsia >= 45;
                    $warna  = $validPerPemain ? '#15803d' : '#dc2626';
                    $icon   = $validPerPemain ? '✓ ' : '✗ ';
                    $label  = $icon . $nilUsia . ' tahun per 24 Ags 2026 — '
                            . ($validPerPemain ? 'Memenuhi syarat (≥ 45 thn)' : 'Tidak memenuhi syarat (min. 45 thn)');
                } else {
                    $warna = '#374151';
                    $label = $nilUsia . ' tahun';
                }

                $usiaHtml = '
                <div style="display:flex;gap:12px;padding:6px 0;border-bottom:1px solid #e5e7eb;">
                    <span style="font-size:11px;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:0.05em;min-width:90px;">Usia</span>
                    <span style="font-size:12px;font-weight:700;color:' . $warna . ';">'
                        . htmlspecialchars($label) .
                    '</span>
                </div>';
            }

            // ── Foto KTP ──
            $fotoHtml = '';
            $filePath = $ktpFiles[$i] ?? null;

            if ($filePath) {
                $ktpUrl = route('admin.ktp.serve', [
                    'uuid'     => $record->uuid,
                    'filename' => basename($filePath),
                ]);
                $fotoHtml = '
                <div>
                    <p style="font-size:11px;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:8px;">📷 Foto KTP</p>
                    <a href="' . $ktpUrl . '" target="_blank" style="display:block;">
                        <img src="' . $ktpUrl . '"
                             alt="KTP Pemain ' . ($i + 1) . '"
                             style="max-height:192px;width:100%;border-radius:8px;border:1px solid #d1d5db;
                                    object-fit:contain;cursor:pointer;background:#f9fafb;transition:opacity 0.2s;"
                             onerror="this.outerHTML=\'<p style=\\\'color:#dc2626;font-size:12px;margin-top:8px;\\\'>File tidak dapat dimuat.</p>\'">
                    </a>
                    <p style="font-size:11px;color:#6b7280;margin-top:4px;">'
                        . htmlspecialchars(basename($filePath))
                        . ' · <a href="' . $ktpUrl . '" target="_blank"
                               style="color:#2563eb;text-decoration:none;">Buka fullsize</a>
                    </p>
                </div>';
            } else {
                $fotoHtml = '<p style="font-size:12px;color:#6b7280;font-style:italic;margin-top:8px;">File KTP tidak ditemukan.</p>';
            }

            // ── Data tambahan dari ktp_data ──
            $rawData   = $ktpData[$i] ?? [];
            $extraHtml = '';
            $extraFields = [
                'kelurahan'         => 'Kel/Desa',
                'kecamatan'         => 'Kecamatan',
                'agama'             => 'Agama',
                'pekerjaan'         => 'Pekerjaan',
                'status_perkawinan' => 'Status Kawin',
                'golongan_darah'    => 'Gol. Darah',
            ];
            foreach ($extraFields as $key => $label) {
                $val = (string) ($rawData[$key] ?? '');
                if ($val === '') continue;
                $extraHtml .= '
                <div style="display:flex;gap:12px;padding:4px 0;border-bottom:1px solid #f3f4f6;">
                    <span style="font-size:11px;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:0.05em;min-width:100px;">'
                        . htmlspecialchars($label) . '</span>
                    <span style="font-size:12px;color:#374151;">'
                        . htmlspecialchars($val) . '</span>
                </div>';
            }

            // ── Warna border kartu ──
            $cardBorderColor = '#d1d5db';
            $headerBgColor   = '#f9fafb';
            if ($isVeteran && $nilUsia !== null) {
                if ($nilUsia >= 45) {
                    $cardBorderColor = '#86efac';
                    $headerBgColor   = '#f0fdf4';
                } else {
                    $cardBorderColor = '#fca5a5';
                    $headerBgColor   = '#fef2f2';
                }
            }

            $html .= '
            <div style="border-radius:12px;border:1px solid ' . $cardBorderColor . ';overflow:hidden;background:#ffffff;">

                <div style="display:flex;align-items:center;gap:12px;padding:12px 16px;border-bottom:1px solid '
                    . $cardBorderColor . ';background:' . $headerBgColor . ';">
                    <div style="width:28px;height:28px;border-radius:50%;background:#e0e7ff;display:flex;
                                align-items:center;justify-content:center;font-size:12px;font-weight:700;
                                color:#4338ca;border:1px solid #c7d2fe;">'
                        . ($i + 1) .
                    '</div>
                    <span style="font-weight:600;font-size:14px;color:#111827;">' . $namaHtml . '</span>
                </div>

                <div style="padding:16px;display:grid;grid-template-columns:1fr 1fr;gap:24px;">

                    <div>
                        <p style="font-size:11px;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:8px;">📋 Data KTP</p>

                        <div style="display:flex;gap:12px;padding:6px 0;border-bottom:1px solid #e5e7eb;">
                            <span style="font-size:11px;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:0.05em;min-width:90px;">NIK</span>
                            <span style="font-size:12px;color:#111827;font-family:monospace;font-weight:700;">' . $nilNik . '</span>
                        </div>
                        <div style="display:flex;gap:12px;padding:6px 0;border-bottom:1px solid #e5e7eb;">
                            <span style="font-size:11px;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:0.05em;min-width:90px;">Nama</span>
                            <span style="font-size:12px;color:#111827;font-weight:600;">' . $namaHtml . '</span>
                        </div>
                        <div style="display:flex;gap:12px;padding:6px 0;border-bottom:1px solid #e5e7eb;">
                            <span style="font-size:11px;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:0.05em;min-width:90px;">Tgl Lahir</span>
                            <span style="font-size:12px;color:#374151;">' . $nilTgl . '</span>
                        </div>
                        ' . $usiaHtml . '

                        ' . ($extraHtml
                            ? '<div style="margin-top:12px;padding-top:8px;border-top:1px solid #e5e7eb;">' . $extraHtml . '</div>'
                            : '') . '
                    </div>

                    <div>' . $fotoHtml . '</div>

                </div>
            </div>';
        }

        $html .= '</div>';
        return $html;
    }

    // ============================================================
    // TABLE
    // ============================================================

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('midtrans_order_id')
                    ->label('Order ID')->searchable()->copyable()
                    ->fontFamily('mono')->size('sm'),

                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama PIC')->searchable()->weight('bold'),

                Tables\Columns\TextColumn::make('tim_pb')
                    ->label('Tim / PB')->searchable(),

                Tables\Columns\TextColumn::make('pemain_list')
                    ->label('Pemain')->limit(40)
                    ->tooltip(fn (Registration $r) => $r->pemain_list)
                    ->searchable(query: fn (Builder $q, string $s) =>
                        $q->whereRaw('JSON_SEARCH(pemain, "one", ?) IS NOT NULL', ["%{$s}%"])
                    ),

                Tables\Columns\BadgeColumn::make('kategori')
                    ->label('Kategori')
                    ->colors([
                        'primary' => 'ganda-dewasa-putra',
                        'info'    => 'ganda-dewasa-putri',
                        'warning' => 'ganda-veteran-putra',
                        'success' => 'beregu',
                    ])
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'ganda-dewasa-putra'  => 'Dewasa Putra',
                        'ganda-dewasa-putri'  => 'Dewasa Putri',
                        'ganda-veteran-putra' => 'Veteran Putra',
                        'beregu'              => 'Beregu',
                        default               => strtoupper($state),
                    }),

                Tables\Columns\TextColumn::make('harga')
                    ->label('Harga')
                    ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.'))
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'paid',
                        'danger'  => 'failed',
                        'gray'    => 'expired',
                    ])
                    ->formatStateUsing(fn ($state) => strtoupper($state)),

                Tables\Columns\IconColumn::make('ktp_scanned')
                    ->label('KTP Scan')->boolean()
                    ->trueIcon('heroicon-o-document-check')
                    ->falseIcon('heroicon-o-document-minus')
                    ->trueColor('success')->falseColor('gray')
                    ->state(fn (Registration $r) => ! empty(array_filter($r->nik ?? [])))
                    ->tooltip(fn (Registration $r) => ! empty(array_filter($r->nik ?? []))
                        ? 'Data KTP ter-scan OCR' : 'Belum di-scan'),

                Tables\Columns\IconColumn::make('veteran_valid')
                    ->label('Syarat Veteran')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-badge')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')->falseColor('danger')
                    ->visible(fn () => true)
                    ->state(function (Registration $r) {
                        if ($r->kategori !== 'ganda-veteran-putra') return null;
                        $usia = $r->usia_pemain ?? [];
                        $u0   = isset($usia[0]) ? (int) $usia[0] : 0;
                        $u1   = isset($usia[1]) ? (int) $usia[1] : 0;
                        return $u0 >= 45 && $u1 >= 45 && ($u0 + $u1) >= 95;
                    })
                    ->tooltip(function (Registration $r) {
                        if ($r->kategori !== 'ganda-veteran-putra') return null;
                        $usia  = $r->usia_pemain ?? [];
                        $u0    = isset($usia[0]) ? (int) $usia[0] : 0;
                        $u1    = isset($usia[1]) ? (int) $usia[1] : 0;
                        $total = $u0 + $u1;
                        return "P1: {$u0} thn · P2: {$u1} thn · Total: {$total} thn (min. 95)";
                    }),

                Tables\Columns\TextColumn::make('jumlah_pemain')
                    ->label('Jml Pemain')
                    ->state(fn (Registration $r) => $r->jumlah_pemain . ' org')
                    ->sortable(false),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Daftar')->dateTime('d M Y, H:i')->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'paid'    => 'Paid',
                        'failed'  => 'Failed',
                        'expired' => 'Expired',
                    ]),

                Tables\Filters\SelectFilter::make('kategori')
                    ->label('Kategori')
                    ->options([
                        'ganda-dewasa-putra'  => 'Ganda Dewasa Putra',
                        'ganda-dewasa-putri'  => 'Ganda Dewasa Putri',
                        'ganda-veteran-putra' => 'Ganda Veteran Putra',
                        'beregu'              => 'Beregu',
                    ]),

                Tables\Filters\Filter::make('ktp_scanned')
                    ->label('Sudah Scan KTP')
                    ->query(fn (Builder $q) =>
                        $q->whereNotNull('nik')->whereRaw('JSON_LENGTH(nik) > 0')
                    ),

                Tables\Filters\Filter::make('veteran_tidak_lolos')
                    ->label('Veteran Tidak Lolos Syarat')
                    ->query(fn (Builder $q) =>
                        $q->where('kategori', 'ganda-veteran-putra')
                          ->whereNotNull('usia_pemain')
                    ),

                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('from')->label('Dari Tanggal'),
                        Forms\Components\DatePicker::make('until')->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when($data['from'],  fn ($q, $d) => $q->whereDate('created_at', '>=', $d))
                            ->when($data['until'], fn ($q, $d) => $q->whereDate('created_at', '<=', $d));
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),

                Tables\Actions\Action::make('lihat_ktp')
                    ->label('Lihat KTP')
                    ->icon('heroicon-o-identification')
                    ->color('info')
                    ->visible(fn (Registration $r) => ! empty($r->ktp_files))
                    ->modalHeading(fn (Registration $r) => 'Foto KTP — ' . $r->nama)
                    ->modalContent(fn (Registration $r): HtmlString => new HtmlString(
                        self::buildKtpModalHtml($r)
                    ))
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup'),

                Tables\Actions\Action::make('download_receipt')
                    ->label('PDF')->icon('heroicon-o-document-arrow-down')->color('gray')
                    ->visible(fn (Registration $r) => $r->status === 'paid' && $r->pdf_receipt_path)
                    ->url(fn (Registration $r) => route('registration.receipt', $r->uuid))
                    ->openUrlInNewTab(),

                Tables\Actions\Action::make('mark_paid')
                    ->label('Tandai Paid')
                    ->icon('heroicon-o-check-circle')->color('success')
                    ->visible(fn (Registration $r) => in_array($r->status, ['pending', 'failed', 'expired']))
                    ->requiresConfirmation()
                    ->action(function (Registration $r) {
                        $r->update(['status' => 'paid', 'payment_time' => now()]);
                        \App\Jobs\ProcessPaidRegistration::dispatch($r);
                        Notification::make()->title('Status diupdate ke Paid')->success()->send();
                    }),

                Tables\Actions\Action::make('resend_email')
                    ->label('Resend Email')
                    ->icon('heroicon-o-envelope')->color('info')
                    ->visible(fn (Registration $r) => $r->status === 'paid' && ! empty($r->email))
                    ->action(function (Registration $r) {
                        \Illuminate\Support\Facades\Mail::to($r->email)
                            ->send(new \App\Mail\RegistrationPaid($r));
                        Notification::make()->title('Email berhasil dikirim ulang')->success()->send();
                    }),

                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->striped()
            ->poll('30s');
    }

    // ── Modal foto KTP ────────────────────────────────────────────

    private static function buildKtpModalHtml(Registration $record): string
    {
        $ktpFiles = $record->ktp_files ?? [];
        $pemain   = $record->pemain   ?? [];

        if (empty($ktpFiles)) {
            return '<p style="color:#6b7280;font-size:14px;padding:16px;">Tidak ada file KTP.</p>';
        }

        $html = '<div style="display:flex;flex-direction:column;gap:24px;padding:8px;">';
        foreach ($ktpFiles as $i => $path) {
            $nama   = htmlspecialchars($pemain[$i] ?? 'Pemain ' . ($i + 1));
            $ktpUrl = route('admin.ktp.serve', [
                'uuid'     => $record->uuid,
                'filename' => basename($path),
            ]);
            $html .= '
            <div>
                <p style="font-size:11px;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:8px;">
                    Pemain ' . ($i + 1) . ' — ' . $nama . '
                </p>
                <a href="' . $ktpUrl . '" target="_blank">
                    <img src="' . $ktpUrl . '" alt="KTP ' . $nama . '"
                         style="width:100%;max-height:256px;object-fit:contain;border-radius:8px;
                                border:1px solid #d1d5db;background:#f9fafb;cursor:pointer;transition:opacity 0.2s;"
                         onerror="this.outerHTML=\'<p style=\\\'color:#dc2626;font-size:12px;\\\'>Gambar tidak dapat dimuat.</p>\'">
                </a>
                <p style="font-size:11px;color:#6b7280;margin-top:4px;">'
                    . htmlspecialchars(basename($path))
                    . ' · Klik untuk buka fullsize</p>
            </div>';
        }
        $html .= '</div>';
        return $html;
    }

    // ============================================================
    // PAGES
    // ============================================================

    public static function getRelations(): array { return []; }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListRegistrations::route('/'),
            'create' => Pages\CreateRegistration::route('/create'),
            'view'   => Pages\ViewRegistration::route('/{record}'),
            'edit'   => Pages\EditRegistration::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'pending')->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }
}