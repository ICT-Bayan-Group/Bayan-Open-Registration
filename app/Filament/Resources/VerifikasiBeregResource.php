<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VerifikasiBeregResource\Pages;
use App\Mail\RegistrationApproved;
use App\Mail\RegistrationRejected;
use App\Mail\RegistrationRevisionRequired;
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
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\HtmlString;

class VerifikasiBeregResource extends Resource
{
    protected static ?string $model            = Registration::class;
    protected static ?string $navigationIcon   = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationLabel  = 'Verifikasi Beregu';
    protected static ?string $modelLabel       = 'Pendaftaran Beregu';
    protected static ?string $pluralModelLabel = 'Verifikasi Beregu';
    protected static ?string $navigationGroup  = 'Verifikasi';
    protected static ?int    $navigationSort   = 2;
    protected static ?string $slug             = 'verifikasi-beregu';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('kategori', 'beregu');
    }

    // ============================================================
    // FORM
    // ============================================================

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Data Tim')->schema([
                Forms\Components\TextInput::make('nama')->label('Nama PIC')->required()->maxLength(100),
                Forms\Components\TextInput::make('tim_pb')->label('Nama Tim')->required()->maxLength(100),
                Forms\Components\TextInput::make('email')->label('Email')->email()->required(),
                Forms\Components\TextInput::make('no_hp')->label('No. HP')->required(),
            ])->columns(2),

            Forms\Components\Section::make('Status')->schema([
                Forms\Components\Select::make('approval_status')->label('Status Approval')
                    ->options([
                        'draft'             => 'Draft',
                        'submitted'         => 'Submitted',
                        'pending_review'    => 'Pending Review',
                        'revision_required' => 'Revision Required',
                        'approved'          => 'Approved',
                        'rejected'          => 'Rejected',
                    ])->required(),
                Forms\Components\Textarea::make('rejection_reason')
                    ->label('Alasan Penolakan')->rows(3)->maxLength(500),
                Forms\Components\Textarea::make('revision_notes')
                    ->label('Catatan Revisi')->rows(3)->maxLength(1000),
            ])->columns(2),
        ]);
    }

    // ============================================================
    // INFOLIST
    // ============================================================

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([

            Infolists\Components\Section::make('Status Pendaftaran')->schema([
Infolists\Components\TextEntry::make('uuid')
                        ->label('ID Pendaftaran')->copyable()->fontFamily('mono')->weight('bold'),

                Infolists\Components\TextEntry::make('approval_status')
                    ->label('Status Approval')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'approved'          => 'success',
                        'pending_review'    => 'warning',
                        'rejected'          => 'danger',
                        'revision_required' => 'info',
                        default             => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'revision_required' => 'PERLU REVISI',
                        default             => strtoupper(str_replace('_', ' ', $state)),
                    }),

                Infolists\Components\TextEntry::make('status')
                    ->label('Status Bayar')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'paid'    => 'success',
                        'pending' => 'warning',
                        default   => 'danger',
                    })
                    ->formatStateUsing(fn (string $state): string => strtoupper($state)),

                Infolists\Components\TextEntry::make('harga')
                    ->label('Total Pembayaran')
                    ->formatStateUsing(fn (string $state): string => 'Rp ' . number_format($state, 0, ',', '.'))
                    ->weight('bold')->color('primary'),

                Infolists\Components\TextEntry::make('approved_at')
                    ->label('Di-approve Pada')->dateTime('d M Y, H:i')->placeholder('—'),

                Infolists\Components\TextEntry::make('approvedBy.name')
                    ->label('Di-approve Oleh')->placeholder('—'),
            ])->columns(3),

            // ── Catatan Revisi ────────────────────────────────────
            Infolists\Components\Section::make('Catatan Revisi Admin')
                ->schema([
                    Infolists\Components\TextEntry::make('revision_notes')
                        ->label('Catatan untuk Peserta')->columnSpanFull()->placeholder('—'),
                    Infolists\Components\TextEntry::make('revisionRequestedBy.name')
                        ->label('Diminta Oleh')->placeholder('—'),
                    Infolists\Components\TextEntry::make('revision_requested_at')
                        ->label('Diminta Pada')->dateTime('d M Y, H:i')->placeholder('—'),
                    Infolists\Components\TextEntry::make('revision_token_expires_at')
                        ->label('Token Kadaluarsa')->dateTime('d M Y, H:i')->placeholder('—'),
                    Infolists\Components\TextEntry::make('revision_submitted_at')
                        ->label('Revisi Dikirim')->dateTime('d M Y, H:i')->placeholder('Belum direvisi'),
                    Infolists\Components\TextEntry::make('revision_count')
                        ->label('Jumlah Revisi')->placeholder('0'),
                ])
                ->columns(2)
                ->collapsible()
                ->visible(fn (Registration $r) => in_array($r->approval_status, ['revision_required', 'pending_review']) && $r->revision_count > 0),

            // ── Alasan Penolakan Final ────────────────────────────
            Infolists\Components\Section::make('Alasan Penolakan')
                ->schema([
                    Infolists\Components\TextEntry::make('rejection_reason')
                        ->label('Alasan')->columnSpanFull()->placeholder('—'),
                    Infolists\Components\TextEntry::make('rejectedBy.name')
                        ->label('Ditolak Oleh')->placeholder('—'),
                    Infolists\Components\TextEntry::make('rejected_at')
                        ->label('Ditolak Pada')->dateTime('d M Y, H:i')->placeholder('—'),
                ])
                ->columns(2)
                ->visible(fn (Registration $r) => $r->approval_status === 'rejected'),

            Infolists\Components\Section::make('Data Tim & Kontak')->schema([
                Infolists\Components\TextEntry::make('nama')->label('Nama PIC')->weight('semibold'),
                Infolists\Components\TextEntry::make('tim_pb')->label('Tim / PB')->weight('semibold'),
                Infolists\Components\TextEntry::make('email')->label('Email')->copyable(),
                Infolists\Components\TextEntry::make('no_hp')->label('No. WhatsApp')->copyable(),
                Infolists\Components\TextEntry::make('provinsi')->label('Provinsi'),
                Infolists\Components\TextEntry::make('kota')->label('Kota'),
            ])->columns(2)->collapsible(),

            Infolists\Components\Section::make('Validasi KTP Anggota (Kota Balikpapan)')
                ->schema([
                    Infolists\Components\TextEntry::make('ktp_validation_summary')
                        ->label('')->html()->columnSpanFull()
                        ->state(fn (Registration $r) => new HtmlString(
                            self::buildValidationSummary($r)
                        )),
                ]),

            Infolists\Components\Section::make('Detail KTP Seluruh Anggota')
                ->schema([
                    Infolists\Components\TextEntry::make('ktp_detail_html')
                        ->label('')->html()->columnSpanFull()
                        ->state(fn (Registration $r) => new HtmlString(
                            self::buildKtpDetail($r)
                        )),
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
                Tables\Columns\TextColumn::make('uuid')
                    ->label('ID')->searchable()->copyable()->fontFamily('mono')->size('sm'),

                Tables\Columns\TextColumn::make('tim_pb')
                    ->label('Nama Tim')->searchable()->weight('bold'),

                Tables\Columns\TextColumn::make('nama')
                    ->label('PIC / Ketua')->searchable(),

                Tables\Columns\TextColumn::make('jumlah_pemain')
                    ->label('Anggota')
                    ->state(fn (Registration $r) => $r->jumlah_pemain . ' orang')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('ktp_valid_count')
                    ->label('KTP Balikpapan')
                    ->state(fn (Registration $r) =>
                        $r->validCityCount() . ' / ' . $r->jumlahPemain . ' valid'
                    )
                    ->badge()
                    ->color(fn (Registration $r) =>
                        $r->validCityCount() >= 6 ? 'success' : 'danger'
                    )
                    ->alignCenter(),

                Tables\Columns\IconColumn::make('meets_minimum')
                    ->label('Syarat Min. 6')
                    ->boolean()
                    ->state(fn (Registration $r) => $r->meetsMinimumValidKtp())
                    ->trueIcon('heroicon-o-check-badge')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->alignCenter()
                    ->tooltip(fn (Registration $r) =>
                        $r->meetsMinimumValidKtp()
                            ? 'Memenuhi syarat (≥6 KTP Balikpapan)'
                            : 'Belum memenuhi syarat (hanya ' . $r->validCityCount() . ' KTP valid)'
                    ),

                Tables\Columns\TextColumn::make('approval_status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft'             => 'gray',
                        'submitted'         => 'info',
                        'pending_review'    => 'warning',
                        'revision_required' => 'info',
                        'approved'          => 'success',
                        'rejected'          => 'danger',
                        default             => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'revision_required' => 'PERLU REVISI',
                        default             => strtoupper(str_replace('_', ' ', $state)),
                    }),

                Tables\Columns\TextColumn::make('revision_count')
                    ->label('Revisi ke-')
                    ->badge()
                    ->color('gray')
                    ->formatStateUsing(fn ($state) => $state > 0 ? '#' . $state : '—')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')->searchable()->copyable()->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Daftar')->dateTime('d M Y, H:i')->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('approval_status')
                    ->label('Status Approval')
                    ->options([
                        'pending_review'    => 'Pending Review',
                        'revision_required' => 'Perlu Revisi',
                        'approved'          => 'Approved',
                        'rejected'          => 'Rejected',
                    ])
                    ->default('pending_review'),

                Tables\Filters\Filter::make('belum_memenuhi_syarat')
                    ->label('Belum Memenuhi Syarat (< 6 KTP Valid)')
                    ->query(fn (Builder $q) =>
                        $q->whereRaw('JSON_LENGTH(ktp_city_valid) > 0')
                    ),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->label('Detail'),

                // ── APPROVE ─────────────────────────────────────
                Tables\Actions\Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Registration $r) => in_array($r->approval_status, ['pending_review']))
                    ->requiresConfirmation()
                    ->modalHeading(fn (Registration $r) => 'Approve Tim: ' . $r->tim_pb)
                    ->modalDescription(fn (Registration $r) =>
                        'KTP valid: ' . $r->validCityCount() . '/' . $r->jumlahPemain . ' anggota. '
                        . 'Link pembayaran akan dikirim ke ' . $r->email . '.'
                    )
                    ->modalSubmitActionLabel('Approve & Kirim Link Pembayaran')
                    ->action(function (Registration $r) {
                        $r->approve(auth()->id());
                        Mail::to($r->email)->send(new RegistrationApproved($r));

                        Notification::make()
                            ->title('✅ ' . $r->tim_pb . ' diapprove!')
                            ->body('Link pembayaran dikirim ke ' . $r->email)
                            ->success()
                            ->send();
                    }),

                // ── REQUEST REVISION (Tolak + Minta Perbaikan) ──
                Tables\Actions\Action::make('request_revision')
                    ->label('Minta Revisi')
                    ->icon('heroicon-o-pencil-square')
                    ->color('warning')
                    ->visible(fn (Registration $r) => in_array($r->approval_status, ['pending_review', 'revision_required']))
                    ->form([
                        Forms\Components\Placeholder::make('info_placeholder')
                            ->label('')
                            ->content(new HtmlString(
                                '<div style="background:rgba(234,179,8,.08);border:1px solid rgba(234,179,8,.3);border-radius:10px;padding:12px 16px;">'
                                . '<p style="margin:0;font-size:13px;color:#92400e;font-weight:600;">💡 Revisi vs Tolak Final</p>'
                                . '<p style="margin:6px 0 0;font-size:12px;color:#78350f;line-height:1.6;">'
                                . 'Gunakan <strong>Minta Revisi</strong> jika data bisa diperbaiki (foto KTP buram, kurang anggota, dll). '
                                . 'Peserta akan mendapat <strong>link perbaikan data</strong> yang aktif 7 hari. '
                                . 'Setelah revisi dikirim, pendaftaran kembali ke status <em>Pending Review</em>.'
                                . '</p></div>'
                            )),

                        Forms\Components\Textarea::make('revision_notes')
                            ->label('Catatan untuk Peserta')
                            ->helperText('Jelaskan secara spesifik apa yang perlu diperbaiki. Peserta akan menerima catatan ini via email.')
                            ->required()
                            ->minLength(20)
                            ->maxLength(1000)
                            ->placeholder("Contoh:\n• Foto KTP Anggota 3 (Budi Santoso) buram, tidak terbaca\n• KTP Anggota 5 bukan KTP Kota Balikpapan (terdeteksi Balikpapan Utara, bukan Kota)\n• Mohon upload ulang dengan foto yang lebih jelas")
                            ->rows(6),

                        Forms\Components\CheckboxList::make('checklist')
                            ->label('Masalah yang Ditemukan (opsional, untuk template)')
                            ->options([
                                'ktp_buram'      => 'Foto KTP buram / tidak terbaca',
                                'detected_seeded' => 'Ditemukan permain seeded A didalam regu',
                                'ktp_under' => 'Umur KTP kurang dari 2 tahun',
                                'ktp_expired'    => 'KTP sudah kadaluarsa',
                            ])
                            ->columns(2),
                    ])
                    ->modalHeading(fn (Registration $r) => '✏ Minta Revisi: ' . $r->tim_pb)
                    ->modalDescription('Peserta akan menerima email dengan catatan perbaikan dan link untuk mengedit ulang data pendaftaran.')
                    ->modalSubmitActionLabel('Kirim Permintaan Revisi')
                    ->modalSubmitAction(fn ($action) => $action->color('warning'))
                    ->action(function (Registration $r, array $data) {
                        // Build full notes (merge checklist + free text)
                        $notes = $data['revision_notes'];
                        if (!empty($data['checklist'])) {
                            $checklistLabels = [
                                'ktp_buram'      => 'Foto KTP buram / tidak terbaca',
                                'detected_seeded' => 'Ditemukan permain seeded A didalam regu',
                                'ktp_under' => 'Umur KTP kurang dari 2 tahun',
                                'ktp_expired'    => 'KTP sudah kadaluarsa',
                            ];
                            $items = array_map(
                                fn ($k) => '• ' . ($checklistLabels[$k] ?? $k),
                                $data['checklist']
                            );
                            $notes = "Poin yang perlu diperbaiki:\n" . implode("\n", $items) . "\n\nCatatan admin:\n" . $notes;
                        }

                        $r->requestRevision(auth()->id(), $notes);
                        Mail::to($r->email)->send(new RegistrationRevisionRequired($r));

                        Notification::make()
                            ->title('✏ Permintaan revisi dikirim ke ' . $r->tim_pb)
                            ->body('Link perbaikan aktif 7 hari. Email dikirim ke ' . $r->email)
                            ->warning()
                            ->send();
                    }),

                // ── REJECT FINAL ─────────────────────────────────
                Tables\Actions\Action::make('reject')
                    ->label('Tolak Final')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn (Registration $r) => in_array($r->approval_status, ['pending_review', 'revision_required']))
                    ->form([
                        Forms\Components\Placeholder::make('reject_info')
                            ->label('')
                            ->content(new HtmlString(
                                '<div style="background:rgba(239,68,68,.06);border:1px solid rgba(239,68,68,.2);border-radius:10px;padding:12px 16px;">'
                                . '<p style="margin:0;font-size:13px;color:#991b1b;font-weight:600;">⚠ Penolakan Final</p>'
                                . '<p style="margin:6px 0 0;font-size:12px;color:#7f1d1d;line-height:1.6;">'
                                . 'Penolakan final <strong>tidak bisa dibatalkan</strong>. '
                                . 'Peserta tidak akan bisa revisi data. '
                                . 'Gunakan <strong>Minta Revisi</strong> jika masalah masih bisa diperbaiki.'
                                . '</p></div>'
                            )),

                        Forms\Components\Textarea::make('rejection_reason')
                            ->label('Alasan Penolakan Final')
                            ->required()
                            ->minLength(10)
                            ->maxLength(500)
                            ->placeholder('Contoh: Hanya 3 anggota ber-KTP Balikpapan. Tidak memenuhi syarat minimal 6 setelah 2x revisi.')
                            ->rows(4),
                    ])
                    ->modalHeading(fn (Registration $r) => '🚫 Tolak Final: ' . $r->tim_pb)
                    ->modalSubmitActionLabel('Tolak Final & Kirim Notifikasi')
                    ->modalSubmitAction(fn ($action) => $action->color('danger'))
                    ->action(function (Registration $r, array $data) {
                        $r->reject(auth()->id(), $data['rejection_reason']);
                        Mail::to($r->email)->send(new RegistrationRejected($r));

                        Notification::make()
                            ->title('❌ ' . $r->tim_pb . ' ditolak final')
                            ->body('Email notifikasi dikirim ke ' . $r->email)
                            ->danger()
                            ->send();
                    }),

                // ── RESEND REVISION LINK ─────────────────────────
                Tables\Actions\Action::make('resend_revision')
                    ->label('Kirim Ulang Link Revisi')
                    ->icon('heroicon-o-arrow-path')
                    ->color('gray')
                    ->visible(fn (Registration $r) => $r->approval_status === 'revision_required')
                    ->requiresConfirmation()
                    ->modalDescription(fn (Registration $r) =>
                        'Token revisi akan diperpanjang 7 hari. Email dikirim ulang ke ' . $r->email
                    )
                    ->action(function (Registration $r) {
                        $r->update(['revision_token_expires_at' => now()->addDays(7)]);
                        Mail::to($r->email)->send(new RegistrationRevisionRequired($r));

                        Notification::make()
                            ->title('Link revisi dikirim ulang ke ' . $r->email)
                            ->success()->send();
                    }),

                // ── RESEND LINK BAYAR ─────────────────────────────
                Tables\Actions\Action::make('resend_link')
                    ->label('Resend Link Bayar')
                    ->icon('heroicon-o-envelope')
                    ->color('info')
                    ->visible(fn (Registration $r) =>
                        $r->approval_status === 'approved' && $r->status !== 'paid'
                    )
                    ->requiresConfirmation()
                    ->modalDescription(fn (Registration $r) =>
                        'Token akan diperpanjang 3 hari. Email dikirim ulang ke ' . $r->email
                    )
                    ->action(function (Registration $r) {
                        $r->update(['payment_token_expires_at' => now()->addDays(3)]);
                        Mail::to($r->email)->send(new RegistrationApproved($r));

                        Notification::make()
                            ->title('Link pembayaran dikirim ulang')
                            ->success()->send();
                    }),

                // ── LIHAT FOTO KTP ────────────────────────────────
                Tables\Actions\Action::make('lihat_ktp')
                    ->label('Foto KTP')
                    ->icon('heroicon-o-identification')
                    ->color('gray')
                    ->visible(fn (Registration $r) => ! empty($r->ktp_files))
                    ->modalHeading(fn (Registration $r) => 'Foto KTP — ' . $r->tim_pb)
                    ->modalContent(fn (Registration $r): HtmlString => new HtmlString(
                        self::buildKtpModal($r)
                    ))
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('bulk_approve')
                        ->label('Approve Terpilih')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(function ($records) {
                            $count = 0;
                            foreach ($records as $r) {
                                if ($r->approval_status === 'pending_review') {
                                    $r->approve(auth()->id());
                                    Mail::to($r->email)->send(new RegistrationApproved($r));
                                    $count++;
                                }
                            }
                            Notification::make()
                                ->title("{$count} tim diapprove & link pembayaran dikirim.")
                                ->success()->send();
                        }),
                ]),
            ])
            ->emptyStateHeading('Tidak ada pendaftaran beregu')
            ->emptyStateDescription('Semua pendaftaran beregu telah diverifikasi.')
            ->emptyStateIcon('heroicon-o-clipboard-document-check')
            ->striped()
            ->poll('20s');
    }

    // ============================================================
    // HTML BUILDERS (same as before — tidak diubah)
    // ============================================================

    private static function buildValidationSummary(Registration $record): string
    {
        $cityValid  = $record->ktp_city_valid ?? [];
        $validCount = $record->validCityCount();
        $total      = $record->jumlahPemain;
        $lolos      = $validCount >= 6;

        $bg    = $lolos ? 'rgba(6,30,18,.85)'    : 'rgba(30,6,6,.85)';
        $br    = $lolos ? 'rgba(52,211,153,.25)'  : 'rgba(248,113,113,.25)';
        $tc    = $lolos ? '#34d399'               : '#f87171';
        $icon  = $lolos ? '✅' : '❌';
        $label = $lolos
            ? "{$validCount} dari {$total} anggota ber-KTP Balikpapan — Memenuhi syarat (min. 6)"
            : "Hanya {$validCount} dari {$total} anggota ber-KTP Balikpapan — Belum memenuhi syarat (min. 6)";

        $grid = '<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:8px;margin-top:16px;">';
        foreach ($cityValid as $item) {
            $ok   = $item['valid']    ?? false;
            $nama = htmlspecialchars($item['nama']     ?? ('Pemain ' . ($item['index'] ?? '?')));
            $city = htmlspecialchars($item['city_raw'] ?? '—');
            $bc   = $ok ? 'rgba(52,211,153,.15)' : 'rgba(248,113,113,.12)';
            $brc  = $ok ? 'rgba(52,211,153,.3)'  : 'rgba(248,113,113,.3)';
            $cc   = $ok ? '#34d399' : '#f87171';
            $ii   = $ok ? '✓' : '✗';

            $grid .= '<div style="background:' . $bc . ';border:1px solid ' . $brc
                . ';border-radius:10px;padding:10px 14px;">'
                . '<div style="display:flex;align-items:center;justify-content:space-between;gap:6px;">'
                . '<span style="font-size:12px;color:rgba(255,255,255,.7);font-weight:600;">'
                . ($item['index'] ?? '?') . '. ' . $nama . '</span>'
                . '<span style="font-size:14px;font-weight:700;color:' . $cc . ';flex-shrink:0;">' . $ii . '</span>'
                . '</div>'
                . '<div style="font-size:11px;color:' . $cc . ';margin-top:4px;font-weight:600;">'
                . ($ok ? 'Balikpapan ✓' : (empty(trim($city)) ? 'Kota tidak terbaca' : '"' . $city . '" — Bukan Balikpapan'))
                . '</div>'
                . '</div>';
        }
        $grid .= '</div>';

        return '<div style="background:' . $bg . ';border:1px solid ' . $br
            . ';border-radius:14px;padding:20px 24px;">'
            . '<div style="font-size:14px;font-weight:800;color:' . $tc . ';margin-bottom:4px;">'
            . $icon . ' ' . $label . '</div>'
            . '<div style="font-size:12px;color:rgba(255,255,255,.35);">Syarat: minimal 6 dari 8 anggota ber-KTP Kota Balikpapan</div>'
            . $grid
            . '</div>';
    }

    private static function buildKtpDetail(Registration $record): string
    {
        $pemain    = $record->pemain         ?? [];
        $nik       = $record->nik            ?? [];
        $tglLahir  = $record->tgl_lahir      ?? [];
        $usia      = $record->usia_pemain    ?? [];
        $ktpFiles  = $record->ktp_files      ?? [];
        $cityValid = $record->ktp_city_valid ?? [];

        if (empty($pemain)) {
            return '<p style="color:#6b7280;font-size:14px;">Belum ada data pemain.</p>';
        }

        $html = '<div style="display:flex;flex-direction:column;gap:16px;">';

        foreach ($pemain as $i => $nama) {
            $cv      = $cityValid[$i] ?? null;
            $ok      = $cv['valid']    ?? false;
            $kotaRaw = htmlspecialchars($cv['city_raw'] ?? '—');

            $borderC  = $ok ? '#059669'  : '#dc2626';
            $headerBg = $ok ? '#f0fdf4'  : '#fef2f2';
            $badgeBg  = $ok ? '#dcfce7'  : '#fee2e2';
            $badgeBr  = $ok ? '#86efac'  : '#fca5a5';
            $badgeTc  = $ok ? '#15803d'  : '#b91c1c';
            $cityLbl  = $ok ? '✓ Balikpapan' : ('✗ ' . ($kotaRaw ?: 'Kota tidak terbaca'));

            $fotoHtml = '';
            $filePath = $ktpFiles[$i] ?? null;
            if ($filePath) {
                $url = route('admin.ktp.serve', ['uuid' => $record->uuid, 'filename' => basename($filePath)]);
                $fotoHtml = '<a href="' . $url . '" target="_blank">'
                    . '<img src="' . $url . '" alt="KTP"'
                    . ' style="width:100%;max-height:180px;object-fit:contain;border-radius:8px;'
                    . 'border:1px solid #e5e7eb;background:#f9fafb;cursor:pointer;">'
                    . '</a>';
            } else {
                $fotoHtml = '<div style="height:100px;display:flex;align-items:center;justify-content:center;'
                    . 'border:1px dashed #d1d5db;border-radius:8px;background:#f9fafb;">'
                    . '<p style="color:#9ca3af;font-size:12px;font-style:italic;">Foto tidak tersedia</p></div>';
            }

            $html .= '<div style="border:1.5px solid ' . $borderC . ';border-radius:12px;overflow:hidden;background:#fff;">'
                . '<div style="display:flex;align-items:center;justify-content:space-between;'
                . 'padding:12px 16px;background:' . $headerBg . ';border-bottom:1.5px solid ' . $borderC . ';">'
                . '<div style="display:flex;align-items:center;gap:10px;">'
                . '<div style="width:28px;height:28px;border-radius:50%;background:#e0e7ff;'
                . 'display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:800;color:#4338ca;">'
                . ($i + 1) . '</div>'
                . '<span style="font-weight:700;font-size:14px;color:#111827;">' . htmlspecialchars($nama) . '</span>'
                . '</div>'
                . '<span style="font-size:11px;font-weight:700;color:' . $badgeTc . ';'
                . 'background:' . $badgeBg . ';border:1px solid ' . $badgeBr . ';'
                . 'border-radius:99px;padding:4px 12px;">' . $cityLbl . '</span>'
                . '</div>'
                . '<div style="padding:16px;display:grid;grid-template-columns:1fr 1fr;gap:16px;background:#fff;">'
                . '<div style="display:flex;flex-direction:column;gap:4px;">'
                . self::ktpRow('NIK',       htmlspecialchars($nik[$i]      ?? '—'), true)
                . self::ktpRow('Nama',      htmlspecialchars($nama))
                . self::ktpRow('Tgl Lahir', htmlspecialchars($tglLahir[$i] ?? '—'))
                . self::ktpRow('Usia',      isset($usia[$i]) ? $usia[$i] . ' tahun' : '—')
                . self::ktpRow('Kota KTP',  $cityLbl, false, $badgeTc)
                . '</div>'
                . '<div>' . $fotoHtml . '</div>'
                . '</div>'
                . '</div>';
        }

        $html .= '</div>';
        return $html;
    }

    private static function ktpRow(string $label, string $value, bool $mono = false, string $color = '#374151'): string
    {
        return '<div style="display:flex;gap:8px;padding:6px 0;border-bottom:1px solid #f3f4f6;">'
            . '<span style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.05em;'
            . 'color:#9ca3af;min-width:76px;flex-shrink:0;padding-top:1px;">' . $label . '</span>'
            . '<span style="font-size:12px;color:' . $color . ';font-weight:' . ($mono ? '700' : '600') . ';'
            . ($mono ? 'font-family:monospace;letter-spacing:.03em;' : '') . 'word-break:break-all;">' . $value . '</span>'
            . '</div>';
    }

    private static function buildKtpModal(Registration $record): string
    {
        $ktpFiles = $record->ktp_files ?? [];
        $pemain   = $record->pemain   ?? [];

        if (empty($ktpFiles)) {
            return '<p style="color:#9ca3af;padding:16px;">Tidak ada file KTP.</p>';
        }

        $html = '<div style="display:flex;flex-direction:column;gap:20px;padding:8px;">';
        foreach ($ktpFiles as $i => $path) {
            $nama  = htmlspecialchars($pemain[$i] ?? 'Pemain ' . ($i + 1));
            $url   = route('admin.ktp.serve', ['uuid' => $record->uuid, 'filename' => basename($path)]);
            $cv    = ($record->ktp_city_valid ?? [])[$i] ?? null;
            $ok    = $cv['valid'] ?? false;
            $badge = $ok
                ? '<span style="color:#34d399;font-size:11px;font-weight:700;">✓ Balikpapan</span>'
                : '<span style="color:#f87171;font-size:11px;font-weight:700;">✗ Bukan Balikpapan</span>';

            $html .= '<div>'
                . '<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;">'
                . '<p style="font-size:11px;font-weight:700;color:#9ca3af;text-transform:uppercase;margin:0;">Pemain ' . ($i + 1) . ' — ' . $nama . '</p>'
                . $badge
                . '</div>'
                . '<a href="' . $url . '" target="_blank">'
                . '<img src="' . $url . '" alt="KTP ' . $nama . '"'
                . ' style="width:100%;max-height:240px;object-fit:contain;border-radius:8px;border:1px solid #374151;background:#0d1117;cursor:pointer;">'
                . '</a>'
                . '</div>';
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
            'index' => Pages\ListVerifikasiBereg::route('/'),
            'view'  => Pages\ViewVerifikasiBereg::route('/{record}'),
            'edit'  => Pages\EditVerifikasiBereg::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        $count = static::getModel()::where('kategori', 'beregu')
            ->whereIn('approval_status', ['pending_review', 'revision_required'])
            ->count();
        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }
}