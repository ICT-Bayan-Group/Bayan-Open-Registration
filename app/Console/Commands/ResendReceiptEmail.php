<?php

namespace App\Console\Commands;

use App\Mail\RegistrationPaid;
use App\Models\Registration;
use App\Services\ReceiptPdfService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class ResendReceiptEmail extends Command
{
    protected $signature = 'receipt:resend {order_id? : Order ID (misal BO2026-XXXXXXXX)} {--all-paid : Kirim ulang ke semua yang sudah paid tapi belum ada PDF}';

    protected $description = 'Regenerate PDF receipt dan kirim ulang email untuk registrasi yang sudah paid';

    public function handle(ReceiptPdfService $pdfService): int
    {
        if ($this->option('all-paid')) {
            $registrations = Registration::where('status', 'paid')
                ->whereNull('pdf_receipt_path')
                ->get();

            if ($registrations->isEmpty()) {
                $this->info('Tidak ada registrasi paid tanpa PDF.');
                return 0;
            }

            $this->info("Memproses {$registrations->count()} registrasi...");

            foreach ($registrations as $reg) {
                $this->processOne($reg, $pdfService);
            }

            return 0;
        }

        $orderId = $this->argument('order_id');

        if (!$orderId) {
            $orderId = $this->ask('Masukkan Order ID (contoh: BO2026-ABCD1234)');
        }

        $registration = Registration::where('midtrans_order_id', $orderId)->first();

        if (!$registration) {
            $this->error("Registrasi dengan Order ID '{$orderId}' tidak ditemukan.");
            return 1;
        }

        if ($registration->status !== 'paid') {
            $this->warn("Status registrasi ini adalah '{$registration->status}', bukan 'paid'.");
            if (!$this->confirm('Lanjutkan quand même?')) {
                return 0;
            }
        }

        $this->processOne($registration, $pdfService);

        return 0;
    }

    private function processOne(Registration $registration, ReceiptPdfService $pdfService): void
    {
        $this->line("→ Memproses: {$registration->midtrans_order_id} ({$registration->nama})");

        // Generate PDF
        try {
            $pdfService->generate($registration);
            $this->info("  ✅ PDF berhasil digenerate");
        } catch (\Exception $e) {
            $this->error("  ❌ Gagal generate PDF: " . $e->getMessage());
        }

        // Kirim email
        try {
            Mail::to($registration->email)->send(new RegistrationPaid($registration->fresh()));
            $this->info("  ✅ Email berhasil dikirim ke {$registration->email}");
        } catch (\Exception $e) {
            $this->error("  ❌ Gagal kirim email: " . $e->getMessage());
        }
    }
}