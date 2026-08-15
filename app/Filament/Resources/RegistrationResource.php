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
use App\Services\WhatsAppService;
use App\Services\ReceiptPdfService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\HtmlString;
use App\Filament\Exports\RegistrationExporter;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Actions\ExportBulkAction;

class RegistrationResource extends Resource
{
    protected static ?string $model            = Registration::class;
    protected static ?string $navigationIcon   = 'heroicon-o-user-group';
    protected static ?string $navigationLabel  = 'Peserta';
    protected static ?string $modelLabel       = 'Peserta';
    protected static ?string $pluralModelLabel = 'Data Peserta';
    protected static ?int    $navigationSort   = 1;
    protected static function showVeteranColumn(): bool
        {
            return true; // default: tampil di semua, nanti di-override false di non-veteran
        }
    private static string $ACTION_PASSWORD_HASH = '$2y$12$31Y9w.yl1C/h/tWdRC.8GuE0hUAvsy3pZPBEUOAHEodSpi4tYw6.6';

    private static function verifyActionPassword(string $input): bool
    {
        return Hash::check($input, self::$ACTION_PASSWORD_HASH);
    }

    private static function passwordField(): Forms\Components\TextInput
    {
        return Forms\Components\TextInput::make('action_password')
            ->label('🔐 Password Admin')
            ->password()
            ->required()
            ->autocomplete('new-password')
            ->helperText('Masukkan password khusus untuk melanjutkan aksi ini');
    }
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
                            $set('harga', $state === 'beregu' ? 1000000 : 400000)
                        ),

                    Forms\Components\TextInput::make('harga')
                        ->label('Harga')->numeric()->prefix('Rp')->readOnly(),

                    Forms\Components\Select::make('status')
                        ->label('Status Pembayaran')
                        ->options([
                            'pending'              => 'Pending',
                            'pending_verification' => 'Menunggu Verifikasi',
                            'paid'                 => 'Paid',
                            'failed'               => 'Failed',
                            'expired'              => 'Expired',
                        ])->required(),

                    Forms\Components\FileUpload::make('payment_proof')
                        ->label('Bukti Pembayaran')
                        ->directory('payment_proofs')
                        ->visibility('public')
                        ->image()
                        ->maxSize(5120)
                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                        ->disabled(),

                    Forms\Components\DateTimePicker::make('payment_verified_at')
                        ->label('Waktu Verifikasi')->readOnly(),

                    Forms\Components\Select::make('payment_verified_by')
                        ->label('Diverifikasi Oleh')
                        ->relationship('paymentVerifiedBy', 'name')
                        ->disabled(),

                    Forms\Components\Textarea::make('payment_note')
                        ->label('Catatan Verifikasi')
                        ->readOnly(),
                ])->columns(2),
        ]);
    }

    // ============================================================
    // INFOLIST (View detail)
    // ============================================================

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([

            Infolists\Components\Section::make('Ringkasan Pendaftaran')
                ->schema([
                    Infolists\Components\TextEntry::make('uuid')
                        ->label('ID Pendaftaran')->copyable()->fontFamily('mono')->weight('bold'),

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
                            'paid'                 => 'success',
                            'pending_verification' => 'warning',
                            'pending'              => 'warning',
                            'failed'               => 'danger',
                            'expired'              => 'gray',
                            default                => 'gray',
                        })
                        ->formatStateUsing(fn ($state) => match ($state) {
                            'pending_verification' => 'Menunggu Verifikasi',
                            'pending'              => 'Belum Bayar',
                            'paid'                 => 'Sudah Bayar',
                            'failed'               => 'Pembayaran Ditolak',
                            'expired'              => 'Kadaluarsa',
                            default                => strtoupper($state),
                        }),

                    Infolists\Components\TextEntry::make('harga')
                        ->label('Total Pembayaran')
                        ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.'))
                        ->weight('bold')->color('primary'),

                    Infolists\Components\TextEntry::make('payment_verified_at')
                        ->label('Waktu Verifikasi')
                        ->dateTime('d M Y, H:i')
                        ->placeholder('-'),

                    Infolists\Components\TextEntry::make('paymentVerifiedBy.name')
                        ->label('Diverifikasi Oleh')
                        ->placeholder('-'),
                ])->columns(3),

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

            Infolists\Components\Section::make('Data Pelatih')
                ->schema([
                    Infolists\Components\TextEntry::make('nama_pelatih')
                        ->label('Nama Pelatih')->placeholder('—'),
                    Infolists\Components\TextEntry::make('no_hp_pelatih')
                        ->label('No. HP Pelatih')->placeholder('—'),
                ])->columns(2)->collapsible(),

            Infolists\Components\Section::make('Verifikasi Usia Veteran')
                ->schema([
                    Infolists\Components\TextEntry::make('veteran_summary_html')
                        ->label('')->html()->columnSpanFull()
                        ->state(fn (Registration $r) => new HtmlString(
                            self::buildVeteranSummaryHtml($r)
                        )),
                ])
                ->visible(fn (Registration $r) => $r->kategori === 'ganda-veteran-putra'),

            Infolists\Components\Section::make('Data Pemain & Dokumen')
                ->schema([
                    Infolists\Components\ViewEntry::make('editable_ktp')
                        ->label('')
                        ->columnSpanFull()
                        ->viewData(fn (Registration $r) => ['record' => $r])
                        ->view('filament.infolists.editable-registration-ktp-wrapper'),
                ]),
        ]);
    }

    // ============================================================
    // TABLE
    // ============================================================

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
               // Tables\Columns\TextColumn::make('uuid')
                 //   ->label('ID Pendaftaran')->searchable()->copyable()
                   // ->fontFamily('mono')->size('sm'),

                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama PIC')->searchable()->weight('bold'),

                Tables\Columns\TextColumn::make('tim_pb')
                    ->label('Tim / PB')->searchable(),

                Tables\Columns\TextColumn::make('pemain_list')
                    ->label('Pemain')->limit(40)
                    ->tooltip(fn (Registration $r) => $r->pemain_list)
                    ->searchable(query: fn (Builder $query, string $search) =>
                        $query->whereRaw('JSON_SEARCH(pemain, "one", ?) IS NOT NULL', ["%{$search}%"])
                    ),

                 //Tables\Columns\BadgeColumn::make('kategori')
                 //    ->label('Kategori')
                  //   ->colors([
                  //       'primary' => 'ganda-dewasa-putra',
                  //       'info'    => 'ganda-dewasa-putri',
                  //       'warning' => 'ganda-veteran-putra',
                  //       'success' => 'beregu',
                  //   ])
                  //   ->formatStateUsing(fn ($state) => match ($state) {
                   //      'ganda-dewasa-putra'  => 'Dewasa Putra',
                   //      'ganda-dewasa-putri'  => 'Dewasa Putri',
                   //      'ganda-veteran-putra' => 'Veteran Putra',
                   //      'beregu'              => 'Beregu',
                  //       default               => strtoupper($state),
                  //   }),

                Tables\Columns\TextColumn::make('harga')
                    ->label('Harga')
                    ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.'))
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'pending',
                        'info'    => 'pending_verification',
                        'success' => 'paid',
                        'danger'  => 'failed',
                        'gray'    => 'expired',
                    ])
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'pending_verification' => 'Verifikasi',
                        'pending'              => 'Pending',
                        'paid'                 => 'Paid',
                        'failed'               => 'Failed',
                        'expired'              => 'Expired',
                        default                => strtoupper($state),
                    }),

                Tables\Columns\TextColumn::make('doc_type_summary')
                    ->label('Dokumen')
                    ->state(function (Registration $r): string {
                        $types  = $r->ktp_type ?? [];
                        $ktp    = count(array_filter($types, fn ($t) => $t !== 'paspor'));
                        $paspor = count(array_filter($types, fn ($t) => $t === 'paspor'));
                        $parts  = [];
                        if ($ktp)    $parts[] = $ktp . ' KTP';
                        if ($paspor) $parts[] = $paspor . ' Paspor';
                        return implode(' + ', $parts) ?: '-';
                    })
                    ->badge()
                    ->color(function (Registration $r): string {
                        $types  = $r->ktp_type ?? [];
                        $paspor = count(array_filter($types, fn ($t) => $t === 'paspor'));
                        return $paspor > 0 ? 'warning' : 'success';
                    }),

                Tables\Columns\IconColumn::make('veteran_valid')
                    ->label('Syarat Veteran')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-badge')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')->falseColor('danger')
                    ->visible(fn () => static::showVeteranColumn()) // ⬅️ tambahan
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
            ->headerActions([
                ExportAction::make()
                    ->label('Export Excel')
                    ->exporter(RegistrationExporter::class)
                    ->formats([ExportFormat::Xlsx]),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending'              => 'Pending',
                        'pending_verification' => 'Menunggu Verifikasi',
                        'paid'                 => 'Paid',
                        'failed'               => 'Failed',
                        'expired'              => 'Expired',
                    ]),

                Tables\Filters\SelectFilter::make('kategori')
                    ->label('Kategori')
                    ->options([
                        'ganda-dewasa-putra'  => 'Ganda Dewasa Putra',
                        'ganda-dewasa-putri'  => 'Ganda Dewasa Putri',
                        'ganda-veteran-putra' => 'Ganda Veteran Putra',
                        'beregu'              => 'Beregu',
                    ]),

                Tables\Filters\Filter::make('has_paspor')
                    ->label('Ada Peserta Paspor')
                    ->query(fn (Builder $q) =>
                        $q->whereRaw('JSON_SEARCH(ktp_type, "one", "paspor") IS NOT NULL')
                    ),

                Tables\Filters\Filter::make('ktp_only')
                    ->label('Semua KTP')
                    ->query(fn (Builder $q) =>
                        $q->whereRaw('JSON_SEARCH(ktp_type, "one", "paspor") IS NULL')
                    ),

                Tables\Filters\Filter::make('veteran_tidak_lolos')
                    ->label('Veteran Tidak Lolos Syarat')
                    ->visible(fn () => static::showVeteranColumn()) // ⬅️ tambahan
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

                // ── Lihat Bukti Pembayaran ───────────────────────
                Tables\Actions\Action::make('view_payment_proof')
                    ->label('Lihat Bukti')
                    ->icon('heroicon-o-photo')
                    ->color('info')
                    ->visible(fn (Registration $r) => $r->hasPaymentProof())
                    ->modalHeading(fn (Registration $r) => 'Bukti Pembayaran — ' . $r->nama)
                    ->modalContent(fn (Registration $r): HtmlString => new HtmlString(
                        self::buildPaymentProofModalHtml($r)
                    ))
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup'),

                // ── Approve Pembayaran ───────────────────────────
                Tables\Actions\Action::make('approve_payment')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Registration $r) => $r->status === 'pending_verification')
                    ->form([
                        Forms\Components\Placeholder::make('info')
                            ->label('')
                            ->content(fn (Registration $r) => new HtmlString(
                                '<div class="confirm-box confirm-success">'
                                . '<p style="font-size:13px;font-weight:600;margin:0 0 4px;" class="txt-ok">Konfirmasi Approve Pembayaran</p>'
                                . '<p style="font-size:12px;margin:0;" class="txt-ok">PDF receipt akan digenerate dan email konfirmasi dikirim ke peserta setelah approve.</p>'
                                . '</div>'
                            )),
                        self::passwordField(),
                    ])
                    ->modalHeading(fn (Registration $r) => 'Approve Pembayaran — ' . $r->nama)
                    ->modalSubmitActionLabel('✓ Approve Sekarang')
                    ->modalWidth('md')
                    ->action(function (Registration $r, array $data, Tables\Actions\Action $action) {
                        if (! self::verifyActionPassword($data['action_password'] ?? '')) {
                            Notification::make()
                                ->title('Password salah')
                                ->body('Anda tidak memiliki akses untuk melakukan aksi ini.')
                                ->danger()
                                ->send();
                            $action->halt();
                            return;
                        }

                        $r->approvePayment(auth()->id());
                        app(ReceiptPdfService::class)->generate($r);
                        \Illuminate\Support\Facades\Mail::to($r->email)
                            ->send(new \App\Mail\RegistrationPaid($r));
                        app(WhatsAppService::class)->sendPaymentSuccess($r);

                        Notification::make()
                            ->title('Pembayaran berhasil di-approve')
                            ->body('Email konfirmasi telah dikirim ke ' . $r->email)
                            ->success()
                            ->send();
                    }),

                // ── Reject Pembayaran ────────────────────────────
                Tables\Actions\Action::make('reject_payment')
                    ->label('Reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn (Registration $r) => $r->status === 'pending_verification')
                    ->form([
                        Forms\Components\Placeholder::make('info')
                            ->label('')
                            ->content(new HtmlString(
                                '<div class="confirm-box confirm-danger">'
                                . '<p style="font-size:13px;font-weight:600;margin:0 0 4px;" class="txt-bad">Konfirmasi Penolakan Pembayaran</p>'
                                . '<p style="font-size:12px;margin:0;" class="txt-bad">Peserta dapat upload ulang bukti pembayaran setelah ditolak.</p>'
                                . '</div>'
                            )),
                        self::passwordField(),
                        Forms\Components\Textarea::make('note')
                            ->label('Alasan Penolakan')
                            ->required()
                            ->maxLength(500)
                            ->placeholder('Contoh: Bukti pembayaran tidak jelas / nominal tidak sesuai')
                            ->helperText('Alasan ini akan dikirim ke peserta via email'),
                    ])
                    ->modalHeading(fn (Registration $r) => 'Tolak Pembayaran — ' . $r->nama)
                    ->modalSubmitActionLabel('✗ Tolak Pembayaran')
                    ->modalWidth('md')
                    ->action(function (Registration $r, array $data, Tables\Actions\Action $action) {
                        if (! self::verifyActionPassword($data['action_password'] ?? '')) {
                            Notification::make()
                                ->title('Password salah')
                                ->body('Anda tidak memiliki akses untuk melakukan aksi ini.')
                                ->danger()
                                ->send();
                            $action->halt();
                            return;
                        }

                        $r->rejectPayment(auth()->id(), $data['note']);
                        \Illuminate\Support\Facades\Mail::to($r->email)
                            ->send(new \App\Mail\RegistrationRejected($r));
                        app(WhatsAppService::class)->sendPaymentRejected($r);

                        Notification::make()
                            ->title('Pembayaran berhasil di-reject')
                            ->body('Email notifikasi telah dikirim ke ' . $r->email)
                            ->success()
                            ->send();
                    }),

                // ── Lihat Dokumen KTP/Paspor ─────────────────────
                Tables\Actions\Action::make('lihat_ktp')
                    ->label('Lihat Dok.')
                    ->icon('heroicon-o-identification')
                    ->color('info')
                    ->visible(fn (Registration $r) => ! empty($r->ktp_files) || ! empty($r->paspor_files) || ! empty($r->paspor_number))
                    ->modalHeading(fn (Registration $r) => 'Dokumen Peserta — ' . $r->nama)
                    ->modalContent(fn (Registration $r): HtmlString => new HtmlString(
                        self::buildKtpModalHtml($r)
                    ))
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup'),

                // ── Download PDF Receipt ─────────────────────────
                Tables\Actions\Action::make('download_receipt')
                    ->label('PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('gray')
                    ->visible(fn (Registration $r) => $r->status === 'paid' && $r->pdf_receipt_path)
                    ->url(fn (Registration $r) => route('registration.receipt', $r->uuid))
                    ->openUrlInNewTab(),

                // ── Resend Link Pembayaran ───────────────────────
                Tables\Actions\Action::make('resend_payment_email')
                    ->label('Resend Link Bayar')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('warning')
                    ->visible(fn (Registration $r) => in_array($r->status, ['pending', 'expired']))
                    ->requiresConfirmation()
                    ->modalHeading('Kirim Ulang Link Pembayaran?')
                    ->modalDescription(fn (Registration $r) =>
                        'Link pembayaran baru akan dibuat dan dikirim ke ' . $r->email . '. Link lama akan otomatis tidak berlaku.'
                    )
                    ->action(function (Registration $r) {
                        $r->regeneratePaymentToken(24);
                        \Illuminate\Support\Facades\Mail::to($r->email)
                            ->send(new \App\Mail\RegistrationPending($r->fresh()));
                        app(WhatsAppService::class)->sendPaymentLink($r->fresh());
                        Notification::make()
                            ->title('Link pembayaran baru dikirim ke ' . $r->email)
                            ->success()
                            ->send();
                    }),

                // ── Resend Email Paid ────────────────────────────
                Tables\Actions\Action::make('resend_email')
                    ->label('Resend Email')
                    ->icon('heroicon-o-envelope')
                    ->color('info')
                    ->visible(fn (Registration $r) => $r->status === 'paid' && ! empty($r->email))
                    ->action(function (Registration $r) {
                        \Illuminate\Support\Facades\Mail::to($r->email)
                            ->send(new \App\Mail\RegistrationPaid($r));
                        Notification::make()
                            ->title('Email berhasil dikirim ulang')
                            ->success()
                            ->send();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    ExportBulkAction::make()
                        ->label('Export Terpilih (Excel)')
                        ->exporter(RegistrationExporter::class)
                        ->formats([ExportFormat::Xlsx]),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->striped()
            ->poll('30s');
    }

    // ============================================================
    // PAYMENT PROOF HTML
    // ============================================================

    private static function buildPaymentProofModalHtml(Registration $record): string
    {
        if (! $record->payment_proof) {
            return '<p class="txt-muted" style="font-size:14px;padding:16px;text-align:center;">Bukti pembayaran tidak tersedia.</p>';
        }

        $url      = asset('storage/' . $record->payment_proof);
        $filename = htmlspecialchars(basename($record->payment_proof));
        $nama     = htmlspecialchars($record->nama);

        return '
        <div style="display:flex;flex-direction:column;align-items:center;gap:16px;padding:8px;">
            <img
                src="' . $url . '"
                alt="Bukti Pembayaran ' . $nama . '"
                class="proof-img"
                onerror="this.outerHTML=\'<div style=\\\'text-align:center;padding:32px;\\\'><p class=\\\'txt-bad\\\' style=\\\'font-size:13px;\\\'>❌ Gambar tidak dapat dimuat.</p></div>\'"
            >
            <p class="txt-muted" style="font-size:11px;margin:0;">' . $filename . '</p>
        </div>';
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

        $v0      = $u0 !== null && $u0 >= 45;
        $v1      = $u1 !== null && $u1 >= 45;
        $totalOk = $total !== null && $total >= 95;
        $allValid = $v0 && $v1 && $totalOk;

        $nama0 = htmlspecialchars($pemain[0] ?? 'Pemain 1');
        $nama1 = htmlspecialchars($pemain[1] ?? 'Pemain 2');

        $boxClass   = $allValid ? 'vet-ok' : 'vet-bad';
        $titleClass = $allValid ? 'txt-ok' : 'txt-bad';
        $titleText  = $allValid
            ? '✓ Kedua pemain memenuhi syarat veteran'
            : '✗ Terdapat pelanggaran syarat veteran';

        $rowHtml = function ($nama, $usia, $valid) {
            $cls  = $valid ? 'txt-ok' : 'txt-bad';
            $icon = $valid ? '✓' : '✗';
            $keterangan = $usia !== null
                ? ($valid
                    ? $usia . ' tahun — Memenuhi syarat (≥ 45 thn)'
                    : $usia . ' tahun — Tidak memenuhi syarat (min. 45 thn)')
                : 'Data usia tidak tersedia';

            return '
            <div class="vet-row">
                <div style="display:flex;align-items:center;gap:8px;">
                    <span class="' . $cls . '" style="font-size:12px;font-weight:700;">' . $icon . '</span>
                    <span class="txt-body" style="font-size:12px;">' . $nama . '</span>
                </div>
                <span class="' . $cls . '" style="font-size:12px;font-weight:700;">' . $keterangan . '</span>
            </div>';
        };

        $totalClass = $totalOk ? 'txt-ok' : 'txt-bad';
        $totalIcon  = $totalOk ? '✓' : '✗';
        $totalText  = $total !== null
            ? ($totalOk
                ? $total . ' tahun — Memenuhi syarat (≥ 95 thn)'
                : $total . ' tahun — Tidak memenuhi syarat (min. 95 thn)')
            : 'Data tidak lengkap';

        return '
        <div class="vet-box ' . $boxClass . '">
            <p class="' . $titleClass . '" style="font-size:12px;font-weight:700;margin-bottom:12px;">' . $titleText . '</p>
            ' . $rowHtml($nama0, $u0, $v0) . '
            ' . $rowHtml($nama1, $u1, $v1) . '
            <div style="display:flex;align-items:center;justify-content:space-between;padding-top:12px;margin-top:4px;">
                <span class="vet-total-label">Total Usia 2 Pemain</span>
                <span class="' . $totalClass . '" style="font-size:14px;font-weight:800;">
                    ' . $totalIcon . ' ' . $totalText . '
                </span>
            </div>
        </div>';
    }

    // ============================================================
    // KTP / PASPOR HTML
    // ============================================================

    private static function buildKtpHtml(Registration $record): string
    {
        $pemain      = $record->pemain        ?? [];
        $nik         = $record->nik           ?? [];
        $pasporNum   = $record->paspor_number ?? [];
        $ktpType     = $record->ktp_type      ?? [];
        $tglLahir    = $record->tgl_lahir     ?? [];
        $usia        = $record->usia_pemain   ?? [];
        $ktpFiles    = $record->ktp_files     ?? [];
        $pasporFiles = $record->paspor_files  ?? [];
        $ktpData     = $record->ktp_data      ?? [];
        $isVeteran   = $record->kategori === 'ganda-veteran-putra';

        if (empty($pemain)) {
            return '<p class="txt-muted" style="font-size:14px;">Belum ada data pemain.</p>';
        }

        $html = '<div style="display:grid;grid-template-columns:1fr;gap:24px;">';

        foreach ($pemain as $i => $nama) {
            $docType  = $ktpType[$i] ?? 'ktp';
            $isPaspor = $docType === 'paspor';

            $namaHtml = htmlspecialchars($nama);
            $nilTgl   = htmlspecialchars($tglLahir[$i] ?? '—');
            $nilUsia  = isset($usia[$i]) ? (int) $usia[$i] : null;

            $docBadge = $isPaspor
                ? '<span class="badge-paspor">🛂 PASPOR</span>'
                : '<span class="badge-ktp">🪪 KTP</span>';

            $usiaHtml = '';
            if ($nilUsia !== null) {
                if ($isVeteran) {
                    $validPerPemain = $nilUsia >= 45;
                    $cls   = $validPerPemain ? 'txt-ok' : 'txt-bad';
                    $label = ($validPerPemain ? '✓ ' : '✗ ') . $nilUsia
                           . ' tahun — ' . ($validPerPemain ? 'Memenuhi syarat (≥ 45 thn)' : 'Tidak memenuhi syarat (min. 45 thn)');
                } else {
                    $cls   = 'txt-body';
                    $label = $nilUsia . ' tahun';
                }
                $usiaHtml = self::infoRow('Usia', $label, $cls, 'bold');
            }

            $docRows = $isPaspor
                ? self::infoRow('No. Paspor', htmlspecialchars($pasporNum[$i] ?? '—'), 'txt-heading', 'bold', 'monospace')
                : self::infoRow('NIK', htmlspecialchars($nik[$i] ?? '—'), 'txt-heading', 'bold', 'monospace');

            // Foto
            $fotoHtml = '';
            if ($isPaspor) {
                $filePath = self::getFilePathForIndex($pasporFiles, $i);
                if ($filePath) {
                    $url = route('admin.paspor.serve', ['uuid' => $record->uuid, 'filename' => basename($filePath)]);
                    $fotoHtml = '
                    <div>
                        <p class="ktp-section-label">📷 Foto Paspor</p>
                        <a href="' . $url . '" target="_blank" style="display:block;">
                            <img src="' . $url . '" alt="Paspor Pemain ' . ($i + 1) . '"
                                 class="ktp-photo" style="max-height:192px;"
                                 onerror="this.outerHTML=\'<p class=\\\'txt-bad\\\' style=\\\'font-size:12px;margin-top:8px;\\\'>File tidak dapat dimuat.</p>\'">
                        </a>
                        <p class="ktp-photo-caption" style="margin-top:4px;">' . htmlspecialchars(basename($filePath)) . ' · <a href="' . $url . '" target="_blank" class="txt-blue" style="text-decoration:none;">Buka fullsize</a></p>
                    </div>';
                } else {
                    $fotoHtml = '<div class="doc-empty-paspor"><span style="font-size:28px;margin-bottom:8px;">🛂</span><p class="txt-purple" style="font-size:12px;font-weight:700;margin:0;">Foto Paspor</p><p class="txt-purple" style="font-size:11px;margin:4px 0 0;">File tidak ditemukan</p></div>';
                }
            } else {
                $filePath = self::getFilePathForIndex($ktpFiles, $i);
                if ($filePath) {
                    $url = route('admin.ktp.serve', ['uuid' => $record->uuid, 'filename' => basename($filePath)]);
                    $fotoHtml = '
                    <div>
                        <p class="ktp-section-label">📷 Foto KTP</p>
                        <a href="' . $url . '" target="_blank" style="display:block;">
                            <img src="' . $url . '" alt="KTP Pemain ' . ($i + 1) . '"
                                 class="ktp-photo" style="max-height:192px;"
                                 onerror="this.outerHTML=\'<p class=\\\'txt-bad\\\' style=\\\'font-size:12px;margin-top:8px;\\\'>File tidak dapat dimuat.</p>\'">
                        </a>
                        <p class="ktp-photo-caption" style="margin-top:4px;">' . htmlspecialchars(basename($filePath)) . ' · <a href="' . $url . '" target="_blank" class="txt-blue" style="text-decoration:none;">Buka fullsize</a></p>
                    </div>';
                } else {
                    $fotoHtml = '<p class="txt-muted" style="font-size:12px;font-style:italic;">File KTP tidak ditemukan.</p>';
                }
            }

            // Extra fields dari ktp_data
            $extraHtml = '';
            if (! $isPaspor) {
                $rawData     = $ktpData[$i] ?? [];
                $extraFields = ['kelurahan' => 'Kel/Desa', 'kecamatan' => 'Kecamatan', 'agama' => 'Agama', 'pekerjaan' => 'Pekerjaan', 'status_perkawinan' => 'Status Kawin', 'golongan_darah' => 'Gol. Darah'];
                foreach ($extraFields as $key => $label) {
                    $val = (string) ($rawData[$key] ?? '');
                    if ($val === '') continue;
                    $extraHtml .= self::infoRow($label, $val);
                }
                if ($extraHtml) {
                    $extraHtml = '<div style="margin-top:12px;padding-top:8px;border-top:1px solid rgba(120,120,120,0.2);">' . $extraHtml . '</div>';
                }
            }

            // Gender
            $rawData      = $ktpData[$i] ?? [];
            $jenisKelamin = $rawData['jenis_kelamin'] ?? null;
            $genderHtml   = '';
            if ($jenisKelamin) {
                $genderLabel = $jenisKelamin === 'L' ? '♂ Laki-laki' : ($jenisKelamin === 'P' ? '♀ Perempuan' : htmlspecialchars($jenisKelamin));
                $genderCls   = $jenisKelamin === 'L' ? 'txt-blue' : 'txt-pink';
                $genderHtml  = self::infoRow('Kelamin', $genderLabel, $genderCls, 'bold');
            }

            // State class kartu
            if ($isPaspor) {
                $stateClass = 'ktp-state-paspor';
            } elseif ($isVeteran && $nilUsia !== null) {
                $stateClass = $nilUsia >= 45 ? 'ktp-state-valid' : 'ktp-state-invalid';
            } else {
                $stateClass = 'ktp-state-neutral';
            }

            $html .= '
            <div class="ktp-card ' . $stateClass . '">
                <div class="ktp-header">
                    <div style="display:flex;align-items:center;gap:12px;">
                        <div class="ktp-avatar">' . ($i + 1) . '</div>
                        <span class="ktp-name">' . $namaHtml . '</span>
                        ' . $docBadge . '
                    </div>
                </div>
                <div style="padding:16px;display:grid;grid-template-columns:1fr 1fr;gap:24px;">
                    <div>
                        <p class="ktp-section-label">' . ($isPaspor ? '📋 Data Paspor' : '📋 Data KTP') . '</p>
                        ' . $docRows . '
                        ' . self::infoRow('Nama', $namaHtml, 'txt-heading', 'semibold') . '
                        ' . self::infoRow('Tgl Lahir', $nilTgl) . '
                        ' . $usiaHtml . '
                        ' . $genderHtml . '
                        ' . $extraHtml . '
                    </div>
                    <div>' . $fotoHtml . '</div>
                </div>
            </div>';
        }

        $html .= '</div>';
        return $html;
    }

    private static function infoRow(
        string $label,
        string $value,
        string $colorClass = 'txt-body',
        string $weight     = 'normal',
        string $fontFamily = 'inherit'
    ): string {
        return '
        <div class="ktp-row">
            <span class="ktp-row-label" style="min-width:90px;">' . htmlspecialchars($label) . '</span>
            <span class="' . $colorClass . '" style="font-size:12px;font-weight:' . $weight . ';font-family:' . $fontFamily . ';">' . $value . '</span>
        </div>';
    }

    private static function getFilePathForIndex(array $files, int $index): ?string
    {
        if (isset($files[$index])) return $files[$index];
        if (count($files) === 1) { $first = reset($files); return $first !== false ? $first : null; }
        return null;
    }

    private static function buildKtpModalHtml(Registration $record): string
    {
        $ktpFiles    = $record->ktp_files     ?? [];
        $pasporFiles = $record->paspor_files  ?? [];
        $pemain      = $record->pemain        ?? [];
        $ktpType     = $record->ktp_type      ?? [];
        $pasporNum   = $record->paspor_number ?? [];

        if (empty($ktpFiles) && empty($pasporFiles) && empty(array_filter($pasporNum ?? []))) {
            return '<p class="txt-muted" style="font-size:14px;padding:16px;">Tidak ada dokumen.</p>';
        }

        $html = '<div style="display:flex;flex-direction:column;gap:24px;padding:8px;">';

        foreach ($pemain as $i => $nama) {
            $docType  = $ktpType[$i] ?? 'ktp';
            $isPaspor = $docType === 'paspor';
            $namaHtml = htmlspecialchars($nama);

            $html .= '<div><p class="ktp-section-label">Pemain ' . ($i + 1) . ' — ' . $namaHtml
                . ($isPaspor
                    ? ' <span class="badge-paspor" style="margin-left:6px;padding:1px 8px;">🛂 Paspor</span>'
                    : ' <span class="badge-ktp" style="margin-left:6px;padding:1px 8px;">🪪 KTP</span>')
                . '</p>';

            if ($isPaspor) {
                $noPass = htmlspecialchars($pasporNum[$i] ?? '—');
                $html  .= '<div class="paspor-num-box"><p class="txt-purple" style="font-size:11px;margin:0 0 4px;">Nomor Paspor</p><p class="txt-purple" style="font-size:18px;font-weight:800;font-family:monospace;margin:0;">' . $noPass . '</p></div>';
                $pasporPath = self::getFilePathForIndex($pasporFiles, $i);
                if ($pasporPath) {
                    $url   = route('admin.paspor.serve', ['uuid' => $record->uuid, 'filename' => basename($pasporPath)]);
                    $html .= '<a href="' . $url . '" target="_blank" style="margin-top:12px;display:block;"><img src="' . $url . '" alt="Paspor ' . $namaHtml . '" class="ktp-photo" style="max-height:256px;" onerror="this.outerHTML=\'<p class=\\\'txt-bad\\\' style=\\\'font-size:12px;\\\'>Gambar tidak dapat dimuat.</p>\'"></a><p class="ktp-photo-caption" style="margin-top:4px;">' . htmlspecialchars(basename($pasporPath)) . ' · Klik untuk buka fullsize</p>';
                } else {
                    $html .= '<p class="txt-muted" style="font-size:12px;font-style:italic;margin-top:8px;">File paspor tidak ditemukan.</p>';
                }
            } else {
                $path = self::getFilePathForIndex($ktpFiles, $i);
                if ($path) {
                    $url   = route('admin.ktp.serve', ['uuid' => $record->uuid, 'filename' => basename($path)]);
                    $html .= '<a href="' . $url . '" target="_blank"><img src="' . $url . '" alt="KTP ' . $namaHtml . '" class="ktp-photo" style="max-height:256px;" onerror="this.outerHTML=\'<p class=\\\'txt-bad\\\' style=\\\'font-size:12px;\\\'>Gambar tidak dapat dimuat.</p>\'"></a><p class="ktp-photo-caption" style="margin-top:4px;">' . htmlspecialchars(basename($path)) . ' · Klik untuk buka fullsize</p>';
                } else {
                    $html .= '<p class="txt-muted" style="font-size:12px;font-style:italic;">File KTP tidak ditemukan.</p>';
                }
            }

            $html .= '</div>';
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

    public static function getWidgets(): array
    {
        return [
            \App\Filament\Resources\RegistrationResource\Widgets\RegistrationStatsOverview::class,
        ];
    }
}