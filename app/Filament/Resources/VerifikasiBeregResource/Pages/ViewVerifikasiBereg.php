<?php
 
namespace App\Filament\Resources\VerifikasiBeregResource\Pages;
 
use App\Filament\Resources\VerifikasiBeregResource;
use App\Mail\RegistrationApproved;
use App\Mail\RegistrationRejected;
use App\Mail\RegistrationRevisionRequired;
use App\Models\Registration;
use Filament\Actions;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\HtmlString;
 
class ViewVerifikasiBereg extends ViewRecord
{
    protected static string $resource = VerifikasiBeregResource::class;
 
    protected function getHeaderActions(): array
    {
        return [
            // ── APPROVE ──────────────────────────────────────────
            Actions\Action::make('approve')
                ->label('Approve & Kirim Link Bayar')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->size('lg')
                ->visible(fn () => $this->record->approval_status === 'pending_review')
                ->requiresConfirmation()
                ->modalHeading('Approve Pendaftaran Beregu')
                ->modalDescription(fn () =>
                    'Tim "' . $this->record->tim_pb . '" — '
                    . $this->record->validCityCount() . '/' . $this->record->jumlahPemain . ' KTP Balikpapan valid. '
                    . 'Link pembayaran dikirim ke: ' . $this->record->email
                )
                ->modalSubmitActionLabel('Ya, Approve & Kirim Email')
                ->action(function () {
                    $this->record->approve(auth()->id());
                    Mail::to($this->record->email)->send(new RegistrationApproved($this->record));
 
                    Notification::make()
                        ->title('✅ Tim "' . $this->record->tim_pb . '" diapprove!')
                        ->body('Link pembayaran dikirim ke ' . $this->record->email)
                        ->success()
                        ->send();
 
                    $this->refreshFormData(['approval_status', 'approved_at', 'payment_token']);
                }),
 
            // ── MINTA REVISI ──────────────────────────────────────
            Actions\Action::make('request_revision')
                ->label('Minta Revisi Data')
                ->icon('heroicon-o-pencil-square')
                ->color('warning')
                ->size('lg')
                ->visible(fn () => in_array($this->record->approval_status, ['pending_review', 'revision_required']))
                ->form([
                    Forms\Components\Placeholder::make('info')
                        ->label('')
                        ->content(new HtmlString(
                            '<div style="background:rgba(234,179,8,.08);border:1px solid rgba(234,179,8,.3);border-radius:10px;padding:14px 16px;margin-bottom:4px;">'
                            . '<p style="margin:0;font-size:13px;font-weight:700;color:#92400e;">💡 Apa itu Minta Revisi?</p>'
                            . '<p style="margin:8px 0 0;font-size:12px;color:#78350f;line-height:1.65;">'
                            . 'Peserta akan mendapat <strong>link perbaikan data</strong> yang aktif selama <strong>7 hari</strong>. '
                            . 'Mereka bisa mengedit data anggota dan upload ulang KTP. '
                            . 'Setelah dikirim, status kembali ke <em>Pending Review</em> untuk dicek ulang.'
                            . '</p></div>'
                        )),

                    Forms\Components\CheckboxList::make('checklist')
                        ->label('Poin yang Perlu Diperbaiki')
                        ->helperText('Centang masalah yang ditemukan untuk membantu peserta')
                        ->options([
                                'ktp_buram'      => 'Foto KTP buram / tidak terbaca',
                                'detected_seeded' => 'Ditemukan permain seeded A didalam regu',
                                'ktp_under' => 'Umur KTP kurang dari 2 tahun',
                                'ktp_expired'    => 'KTP sudah kadaluarsa',
                        ])
                        ->columns(2),

                    Forms\Components\Textarea::make('revision_notes')
                        ->label('Catatan Detail untuk Peserta')
                        ->helperText('Peserta akan menerima catatan ini persis di email mereka.')
                        ->required()
                        ->minLength(20)
                        ->maxLength(1000)
                        ->placeholder("Contoh:\n• Foto KTP Anggota 3 (Budi) buram, tidak terbaca sistem\n• Anggota 6 KTP-nya terdeteksi kota lain\nMohon upload ulang foto yang lebih jelas dan pastikan seluruh isi KTP terlihat.")
                        ->rows(6),
                ])
                ->modalHeading(fn () => '✏ Minta Revisi: Tim ' . $this->record->tim_pb)
                ->modalDescription('Email permintaan revisi + link perbaikan akan dikirim ke peserta.')
                ->modalSubmitActionLabel('Kirim Permintaan Revisi')
                ->modalSubmitAction(fn ($action) => $action->color('warning'))
                ->action(function (array $data) {
                    $notes = $data['revision_notes'];
                    if (!empty($data['checklist'])) {
                        $labels = [
                                'ktp_buram'      => 'Foto KTP buram / tidak terbaca',
                                'detected_seeded' => 'Ditemukan permain seeded A didalam regu',
                                'ktp_under' => 'Umur KTP kurang dari 2 tahun',
                                'ktp_expired'    => 'KTP sudah kadaluarsa',
                        ];
                        $items = array_map(fn ($k) => '• ' . ($labels[$k] ?? $k), $data['checklist']);
                        $notes = "Poin yang perlu diperbaiki:\n" . implode("\n", $items) . "\n\nCatatan admin:\n" . $notes;
                    }

                    $this->record->requestRevision(auth()->id(), $notes);
                    Mail::to($this->record->email)->send(new RegistrationRevisionRequired($this->record));
 
                    Notification::make()
                        ->title('✏ Permintaan revisi dikirim!')
                        ->body('Link aktif 7 hari. Email dikirim ke ' . $this->record->email)
                        ->warning()
                        ->send();
 
                    $this->refreshFormData(['approval_status', 'revision_notes', 'revision_requested_at']);
                }),
 
            // ── REJECT FINAL ─────────────────────────────────────
            Actions\Action::make('reject')
                ->label('Tolak Final')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->size('lg')
                ->visible(fn () => in_array($this->record->approval_status, ['pending_review', 'revision_required']))
                ->form([
                    Forms\Components\Textarea::make('rejection_reason')
                        ->label('Alasan Penolakan Final')
                        ->required()
                        ->minLength(10)
                        ->maxLength(500)
                        ->placeholder('Contoh: Setelah 2x revisi, hanya 4 anggota ber-KTP Balikpapan. Tidak memenuhi syarat.')
                        ->rows(4),
                ])
                ->modalHeading('🚫 Tolak Final Pendaftaran')
                ->modalSubmitActionLabel('Tolak & Kirim Email')
                ->modalSubmitAction(fn ($action) => $action->color('danger'))
                ->action(function (array $data) {
                    $this->record->reject(auth()->id(), $data['rejection_reason']);
                    Mail::to($this->record->email)->send(new RegistrationRejected($this->record));
 
                    Notification::make()
                        ->title('❌ Pendaftaran "' . $this->record->tim_pb . '" ditolak final')
                        ->body('Notifikasi dikirim ke ' . $this->record->email)
                        ->danger()
                        ->send();
 
                    $this->refreshFormData(['approval_status', 'rejected_at', 'rejection_reason']);
                }),
 
            // ── RESEND ───────────────────────────────────────────
            Actions\Action::make('resend')
                ->label('Kirim Ulang Link Bayar')
                ->icon('heroicon-o-envelope')
                ->color('info')
                ->visible(fn () =>
                    $this->record->approval_status === 'approved'
                    && $this->record->status !== 'paid'
                )
                ->action(function () {
                    $this->record->update(['payment_token_expires_at' => now()->addDays(3)]);
                    Mail::to($this->record->email)->send(new RegistrationApproved($this->record));
 
                    Notification::make()
                        ->title('Link pembayaran dikirim ulang')
                        ->success()->send();
                }),
 
            Actions\EditAction::make()->label('Edit Data'),
        ];
    }
}