<?php

namespace App\Console\Commands;

use App\Models\Registration;
use App\Services\WhatsAppService;
use Illuminate\Console\Command;

class SendPaymentReminders extends Command
{
    protected $signature = 'whatsapp:send-reminders';

    protected $description = 'Kirim reminder WhatsApp kepada peserta yang belum menyelesaikan pembayaran setelah 6 jam.';

    public function handle(WhatsAppService $whatsapp): int
    {
        $registrations = Registration::where('approval_status', 'approved')
            ->whereIn('status', ['pending', 'failed'])
            ->where('created_at', '<=', now()->subHours(6))
            ->where(function ($query) {
                $query->whereNull('whatsapp_reminder_sent')
                      ->orWhere('whatsapp_reminder_sent', false);
            })
            ->get();

        if ($registrations->isEmpty()) {
            $this->info('Tidak ada registrasi yang perlu reminder WhatsApp.');
            return 0;
        }

        $this->info('Mengirim reminder WhatsApp ke ' . $registrations->count() . ' registrasi.');

        foreach ($registrations as $registration) {
            $sent = $whatsapp->sendReminder($registration);
            $this->line(sprintf(
                '[%s] %s → %s',
                $registration->uuid,
                $registration->email,
                $sent ? 'Dikirim' : 'Gagal'
            ));
        }

        return 0;
    }
}
