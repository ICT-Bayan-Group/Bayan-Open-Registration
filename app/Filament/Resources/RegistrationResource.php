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
    // Tampil di section khusus — ringkasan usia + total
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

        $borderColor = $allValid ? 'rgba(52,211,153,0.25)' : 'rgba(248,113,113,0.25)';
        $bgColor     = $allValid ? 'rgba(6,30,18,0.6)'     : 'rgba(30,6,6,0.6)';
        $titleColor  = $allValid ? '#34d399' : '#f87171';
        $titleText   = $allValid
            ? '✓ Kedua pemain memenuhi syarat veteran'
            : '✗ Terdapat pelanggaran syarat veteran';

        $rowHtml = function ($nama, $usia, $valid) {
            $warna = $valid ? '#34d399' : '#f87171';
            $icon  = $valid ? '✓' : '✗';
            $keterangan = $usia !== null
                ? ($valid
                    ? $usia . ' tahun — Memenuhi syarat (≥ 45 thn)'
                    : $usia . ' tahun — Tidak memenuhi syarat (min. 45 thn)')
                : 'Data usia tidak tersedia';

            return '
            <div class="flex items-center justify-between py-2 border-b border-white/5 last:border-0">
                <div class="flex items-center gap-2">
                    <span class="text-xs font-bold" style="color:' . $warna . ';">' . $icon . '</span>
                    <span class="text-xs text-white/70">' . $nama . '</span>
                </div>
                <span class="text-xs font-bold" style="color:' . $warna . ';">' . $keterangan . '</span>
            </div>';
        };

        // Baris total usia
        $totalColor = $totalOk ? '#34d399' : '#f87171';
        $totalIcon  = $totalOk ? '✓' : '✗';
        $totalText  = $total !== null
            ? ($totalOk
                ? $total . ' tahun — Memenuhi syarat (≥ 95 thn)'
                : $total . ' tahun — Tidak memenuhi syarat (min. 95 thn)')
            : 'Data tidak lengkap';

        $html = '
        <div style="background:' . $bgColor . ';border:1px solid ' . $borderColor . ';border-radius:12px;padding:16px;">

            <p class="text-xs font-bold mb-3" style="color:' . $titleColor . ';">'
                . $titleText . '
            </p>

            ' . $rowHtml($nama0, $u0, $v0) . '
            ' . $rowHtml($nama1, $u1, $v1) . '

            <div class="flex items-center justify-between pt-3 mt-1">
                <span class="text-xs font-semibold text-white/40 uppercase tracking-wide">Total Usia 2 Pemain</span>
                <span class="text-sm font-extrabold" style="color:' . $totalColor . ';">
                    ' . $totalIcon . ' ' . $totalText . '
                </span>
            </div>
        </div>';

        return $html;
    }

    // ============================================================
    // KTP PER PEMAIN HTML
    // Tampil NIK, Tgl Lahir, Usia (+ validasi warna untuk veteran)
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
            return '<p class="text-gray-400 text-sm">Belum ada data pemain.</p>';
        }

        $html = '<div class="grid grid-cols-1 gap-6">';

        foreach ($pemain as $i => $nama) {
            $nilNik   = htmlspecialchars($nik[$i]      ?? '—');
            $nilTgl   = htmlspecialchars($tglLahir[$i] ?? '—');
            $nilUsia  = isset($usia[$i]) ? (int) $usia[$i] : null;
            $namaHtml = htmlspecialchars($nama);

            // ── Baris usia (semua kategori) ──
            $usiaHtml = '';
            if ($nilUsia !== null) {
                if ($isVeteran) {
                    $validPerPemain = $nilUsia >= 45;
                    $warna  = $validPerPemain ? '#34d399' : '#f87171';
                    $icon   = $validPerPemain ? '✓ ' : '✗ ';
                    $label  = $icon . $nilUsia . ' tahun per 24 Ags 2026 — '
                            . ($validPerPemain ? 'Memenuhi syarat (≥ 45 thn)' : 'Tidak memenuhi syarat (min. 45 thn)');
                } else {
                    $warna = 'rgba(255,255,255,0.7)';
                    $label = $nilUsia . ' tahun';
                }

                $usiaHtml = '
                <div class="flex gap-3 py-1.5 border-b border-gray-700/40">
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide min-w-[90px]">Usia</span>
                    <span class="text-xs font-bold" style="color:' . $warna . ';">'
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
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-2">📷 Foto KTP</p>
                    <a href="' . $ktpUrl . '" target="_blank" class="block">
                        <img src="' . $ktpUrl . '"
                             alt="KTP Pemain ' . ($i + 1) . '"
                             class="max-h-48 w-full rounded-lg border border-gray-600 object-contain
                                    cursor-pointer hover:opacity-90 transition"
                             style="background:#111;"
                             onerror="this.outerHTML=\'<p class=\\\'text-xs text-red-400 mt-2\\\'>File tidak dapat dimuat.</p>\'">
                    </a>
                    <p class="text-xs text-gray-500 mt-1">'
                        . htmlspecialchars(basename($filePath))
                        . ' · <a href="' . $ktpUrl . '" target="_blank"
                               class="text-blue-400 hover:underline">Buka fullsize</a>
                    </p>
                </div>';
            } else {
                $fotoHtml = '<p class="text-xs text-gray-500 italic mt-2">File KTP tidak ditemukan.</p>';
            }

            // ── Data tambahan dari ktp_raw ──
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
                <div class="flex gap-3 py-1 border-b border-gray-700/30 last:border-0">
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide min-w-[100px]">'
                        . htmlspecialchars($label) . '</span>
                    <span class="text-xs text-gray-300">'
                        . htmlspecialchars($val) . '</span>
                </div>';
            }

            // ── Warna border kartu: veteran valid/invalid, lainnya netral ──
            $cardBorder = 'border-gray-700';
            if ($isVeteran && $nilUsia !== null) {
                $cardBorder = $nilUsia >= 45 ? 'border-emerald-700/50' : 'border-red-700/50';
            }

            $html .= '
            <div class="rounded-xl border ' . $cardBorder . ' overflow-hidden"
                 style="background:rgba(255,255,255,0.02);">

                <div class="flex items-center gap-3 px-4 py-3 border-b ' . $cardBorder . '"
                     style="background:rgba(255,255,255,0.03);">
                    <div class="w-7 h-7 rounded-full bg-indigo-500/20 flex items-center justify-center
                                text-xs font-bold text-indigo-400 border border-indigo-500/30">'
                        . ($i + 1) .
                    '</div>
                    <span class="font-semibold text-sm text-white">' . $namaHtml . '</span>
                </div>

                <div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-6">

                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-2">📋 Data KTP</p>

                        <div class="flex gap-3 py-1.5 border-b border-gray-700/40">
                            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide min-w-[90px]">NIK</span>
                            <span class="text-xs text-white font-mono font-bold">' . $nilNik . '</span>
                        </div>
                        <div class="flex gap-3 py-1.5 border-b border-gray-700/40">
                            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide min-w-[90px]">Nama</span>
                            <span class="text-xs text-white font-semibold">' . $namaHtml . '</span>
                        </div>
                        <div class="flex gap-3 py-1.5 border-b border-gray-700/40">
                            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide min-w-[90px]">Tgl Lahir</span>
                            <span class="text-xs text-gray-200">' . $nilTgl . '</span>
                        </div>
                        ' . $usiaHtml . '

                        ' . ($extraHtml
                            ? '<div class="mt-3 pt-2 border-t border-gray-700/40">' . $extraHtml . '</div>'
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

                // Kolom khusus veteran: indikator syarat usia terpenuhi
                Tables\Columns\IconColumn::make('veteran_valid')
                    ->label('Syarat Veteran')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-badge')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')->falseColor('danger')
                    ->visible(fn () => true) // selalu tampil, tapi hanya ada value untuk veteran
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

                // Filter: veteran yang tidak lolos syarat usia
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
            return '<p class="text-gray-400 text-sm p-4">Tidak ada file KTP.</p>';
        }

        $html = '<div class="space-y-6 p-2">';
        foreach ($ktpFiles as $i => $path) {
            $nama   = htmlspecialchars($pemain[$i] ?? 'Pemain ' . ($i + 1));
            $ktpUrl = route('admin.ktp.serve', [
                'uuid'     => $record->uuid,
                'filename' => basename($path),
            ]);
            $html .= '
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-2">
                    Pemain ' . ($i + 1) . ' — ' . $nama . '
                </p>
                <a href="' . $ktpUrl . '" target="_blank">
                    <img src="' . $ktpUrl . '" alt="KTP ' . $nama . '"
                         class="w-full max-h-64 object-contain rounded-lg border border-gray-600
                                hover:opacity-90 transition cursor-pointer"
                         style="background:#0d1117;"
                         onerror="this.outerHTML=\'<p class=\\\'text-red-400 text-xs\\\'>Gambar tidak dapat dimuat.</p>\'">
                </a>
                <p class="text-xs text-gray-500 mt-1">'
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