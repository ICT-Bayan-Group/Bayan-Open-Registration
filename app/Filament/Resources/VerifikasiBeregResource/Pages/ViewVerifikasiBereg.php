<?php
 
namespace App\Filament\Resources\VerifikasiBeregResource\Pages;
 
use App\Filament\Resources\VerifikasiBeregResource;
use App\Mail\RegistrationApproved;
use App\Mail\RegistrationRejected;
use App\Models\Registration;
use Filament\Actions;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Mail;
 
class ViewVerifikasiBereg extends ViewRecord
{
    protected static string $resource = VerifikasiBeregResource::class;
 
    /**
     * Tombol Approve & Reject langsung di header halaman detail.
     */
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
 
            // ── REJECT ───────────────────────────────────────────
            Actions\Action::make('reject')
                ->label('Tolak Pendaftaran')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->size('lg')
                ->visible(fn () => $this->record->approval_status === 'pending_review')
                ->form([
                    Forms\Components\Textarea::make('rejection_reason')
                        ->label('Alasan Penolakan')
                        ->required()
                        ->minLength(10)
                        ->maxLength(500)
                        ->placeholder('Contoh: Hanya 4 anggota ber-KTP Balikpapan, kurang dari syarat minimal 6.')
                        ->rows(4),
                ])
                ->modalHeading('Tolak Pendaftaran')
                ->modalSubmitActionLabel('Tolak & Kirim Email')
                ->modalSubmitAction(fn ($a) => $a->color('danger'))
                ->action(function (array $data) {
                    $this->record->reject(auth()->id(), $data['rejection_reason']);
                    Mail::to($this->record->email)->send(new RegistrationRejected($this->record));
 
                    Notification::make()
                        ->title('❌ Pendaftaran "' . $this->record->tim_pb . '" ditolak')
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