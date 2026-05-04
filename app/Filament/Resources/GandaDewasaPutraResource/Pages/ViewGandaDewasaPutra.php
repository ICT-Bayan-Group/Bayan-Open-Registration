<?php

namespace App\Filament\Resources\GandaDewasaPutraResource\Pages;

use App\Filament\Resources\GandaDewasaPutraResource;
use Filament\Actions;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\HtmlString;

class ViewGandaDewasaPutra extends ViewRecord
{
    protected static string $resource = GandaDewasaPutraResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),

            // ── Lihat Bukti Pembayaran ───────────────────────
            Actions\Action::make('view_payment_proof')
                ->label('Lihat Bukti')
                ->icon('heroicon-o-photo')
                ->color('info')
                ->visible(fn () => $this->getRecord()->hasPaymentProof())
                ->modalHeading(fn () => 'Bukti Pembayaran — ' . $this->getRecord()->nama)
                ->modalContent(fn (): HtmlString => new HtmlString(
                    $this->buildPaymentProofModalHtml($this->getRecord())
                ))
                ->modalSubmitAction(false)
                ->modalCancelActionLabel('Tutup'),

            // ── Approve Pembayaran ───────────────────────────
            Actions\Action::make('approve_payment')
                ->label('Approve')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->visible(fn () => $this->getRecord()->status === 'pending_verification')
                ->form([
                    Forms\Components\Placeholder::make('info')
                        ->label('')
                        ->content(fn (): HtmlString => new HtmlString(
                            '<div style="padding:12px 14px;border-radius:10px;background:#f0fdf4;border:1px solid #86efac;margin-bottom:4px;">'
                            . '<p style="font-size:13px;font-weight:600;color:#15803d;margin:0 0 4px;">Konfirmasi Approve Pembayaran</p>'
                            . '<p style="font-size:12px;color:#166534;margin:0;">PDF receipt akan digenerate dan email konfirmasi dikirim ke peserta setelah approve.</p>'
                            . '</div>'
                        )),
                    Forms\Components\TextInput::make('action_password')
                        ->label('🔐 Password Admin')
                        ->password()
                        ->required()
                        ->autocomplete('new-password')
                        ->helperText('Masukkan password khusus untuk melanjutkan aksi ini'),
                ])
                ->modalHeading(fn () => 'Approve Pembayaran — ' . $this->getRecord()->nama)
                ->modalSubmitActionLabel('✓ Approve Sekarang')
                ->modalWidth('md')
                ->action(function (array $data, Actions\Action $action) {
                    if (! $this->verifyActionPassword($data['action_password'] ?? '')) {
                        Notification::make()
                            ->title('Password salah')
                            ->body('Anda tidak memiliki akses untuk melakukan aksi ini.')
                            ->danger()
                            ->send();
                        $action->halt();
                        return;
                    }

                    $record = $this->getRecord();
                    $record->approvePayment(auth()->id());
                    app(\App\Services\ReceiptPdfService::class)->generate($record);
                    \Illuminate\Support\Facades\Mail::to($record->email)
                        ->send(new \App\Mail\RegistrationPaid($record));
                    app(\App\Services\WhatsAppService::class)->sendPaymentSuccess($record);

                    Notification::make()
                        ->title('Pembayaran berhasil di-approve')
                        ->body('Email konfirmasi telah dikirim ke ' . $record->email)
                        ->success()
                        ->send();

                 return redirect()->to(request()->header('referer') ?? url()->current());
                }),

            // ── Reject Pembayaran ────────────────────────────
            Actions\Action::make('reject_payment')
                ->label('Reject')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->visible(fn () => $this->getRecord()->status === 'pending_verification')
                ->form([
                    Forms\Components\Placeholder::make('info')
                        ->label('')
                        ->content(new HtmlString(
                            '<div style="padding:12px 14px;border-radius:10px;background:#fef2f2;border:1px solid #fca5a5;margin-bottom:4px;">'
                            . '<p style="font-size:13px;font-weight:600;color:#dc2626;margin:0 0 4px;">Konfirmasi Penolakan Pembayaran</p>'
                            . '<p style="font-size:12px;color:#991b1b;margin:0;">Peserta dapat upload ulang bukti pembayaran setelah ditolak.</p>'
                            . '</div>'
                        )),
                    Forms\Components\TextInput::make('action_password')
                        ->label('🔐 Password Admin')
                        ->password()
                        ->required()
                        ->autocomplete('new-password')
                        ->helperText('Masukkan password khusus untuk melanjutkan aksi ini'),
                    Forms\Components\Textarea::make('note')
                        ->label('Alasan Penolakan')
                        ->required()
                        ->maxLength(500)
                        ->placeholder('Contoh: Bukti pembayaran tidak jelas / nominal tidak sesuai')
                        ->helperText('Alasan ini akan dikirim ke peserta via email'),
                ])
                ->modalHeading(fn () => 'Tolak Pembayaran — ' . $this->getRecord()->nama)
                ->modalSubmitActionLabel('✗ Tolak Pembayaran')
                ->modalWidth('md')
                ->action(function (array $data, Actions\Action $action) {
                    if (! $this->verifyActionPassword($data['action_password'] ?? '')) {
                        Notification::make()
                            ->title('Password salah')
                            ->body('Anda tidak memiliki akses untuk melakukan aksi ini.')
                            ->danger()
                            ->send();
                        $action->halt();
                        return;
                    }

                    $record = $this->getRecord();
                    $record->rejectPayment(auth()->id(), $data['note']);
                    \Illuminate\Support\Facades\Mail::to($record->email)
                        ->send(new \App\Mail\RegistrationRejected($record));
                    app(\App\Services\WhatsAppService::class)->sendPaymentRejected($record);

                    Notification::make()
                        ->title('Pembayaran berhasil di-reject')
                        ->body('Email notifikasi telah dikirim ke ' . $record->email)
                        ->success()
                        ->send();

                   return redirect()->to(request()->header('referer') ?? url()->current());
                }),
        ];
    }

    private function verifyActionPassword(string $input): bool
    {
        return \Illuminate\Support\Facades\Hash::check($input, '$2y$12$31Y9w.yl1C/h/tWdRC.8GuE0hUAvsy3pZPBEUOAHEodSpi4tYw6.6');
    }

    private function buildPaymentProofModalHtml(\App\Models\Registration $record): string
    {
        if (! $record->payment_proof) {
            return '<p style="color:#6b7280;font-size:14px;padding:16px;text-align:center;">Bukti pembayaran tidak tersedia.</p>';
        }

        $path = storage_path('app/public/' . $record->payment_proof);
        if (! file_exists($path)) {
            return '<p style="color:#dc2626;font-size:14px;padding:16px;text-align:center;">File bukti pembayaran tidak ditemukan di server.</p>';
        }

        $url = asset('storage/' . $record->payment_proof);
        $html = '<div style="text-align:center;padding:16px;">';

        $html .= '<a href="' . $url . '" target="_blank"><img src="' . $url . '" alt="Bukti Pembayaran ' . htmlspecialchars($record->nama) . '" style="width:100%;max-height:400px;object-fit:contain;border-radius:8px;border:1px solid #d1d5db;background:#f9fafb;cursor:pointer;" onerror="this.outerHTML=\'<p style=\\\'color:#dc2626;font-size:12px;\\\'>Gambar tidak dapat dimuat.</p>\'"></a><p style="font-size:11px;color:#6b7280;margin-top:8px;">' . htmlspecialchars(basename($record->payment_proof)) . ' · Klik untuk buka fullsize</p>';

        $html .= '</div>';
        return $html;
    }
}