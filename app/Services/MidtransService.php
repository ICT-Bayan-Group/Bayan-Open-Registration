<?php

namespace App\Services;

use App\Mail\RegistrationPaid;
use App\Models\Registration;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Midtrans\Config;
use Midtrans\Snap;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey    = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production', false);
        Config::$isSanitized  = true;
        Config::$is3ds        = true;
    }

    public function createSnapToken(Registration $registration): string
    {
        $params = [
            'transaction_details' => [
                'order_id'     => $registration->midtrans_order_id,
                'gross_amount' => $registration->harga,
            ],
            'customer_details' => [
                'first_name' => $registration->nama,
                'email'      => $registration->email ?? 'noreply@bayanopen.com',
                'phone'      => $registration->no_hp,
            ],
            'item_details' => [
                [
                    'id'       => $registration->kategori,
                    'price'    => $registration->harga,
                    'quantity' => 1,
                    'name'     => 'Bayan Open 2026 - ' . $registration->kategori_label,
                ],
            ],
        ];

        return Snap::getSnapToken($params);
    }

    public function verifySignature(string $orderId, string $statusCode, string $grossAmount, string $signatureKey): bool
    {
        $serverKey = config('services.midtrans.server_key');
        $expected  = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);
        return hash_equals($expected, $signatureKey);
    }

    public function handleCallback(array $payload): void
    {
        // Verify signature
        $serverKey    = config('services.midtrans.server_key');
        $orderId      = $payload['order_id'];
        $statusCode   = $payload['status_code'];
        $grossAmount  = $payload['gross_amount'];
        $expectedSig  = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        if ($expectedSig !== $payload['signature_key']) {
            throw new \Exception('Invalid signature');
        }

        $registration = Registration::where('midtrans_order_id', $orderId)->firstOrFail();

        $transactionStatus = $payload['transaction_status'];
        $fraudStatus       = $payload['fraud_status'] ?? null;

        $newStatus = match (true) {
            $transactionStatus === 'capture' && $fraudStatus === 'accept' => 'paid',
            $transactionStatus === 'settlement'                           => 'paid',
            in_array($transactionStatus, ['cancel', 'deny', 'expire'])    => 'expired',
            $transactionStatus === 'pending'                              => 'pending',
            default                                                       => $registration->status,
        };

        $wasPending = $registration->status !== 'paid';

        $registration->update([
            'status'                  => $newStatus,
            'midtrans_transaction_id' => $payload['transaction_id'] ?? null,
            'payment_type'            => $payload['payment_type']   ?? null,
            'payment_time'            => $payload['settlement_time'] ?? $payload['transaction_time'] ?? null,
            'fraud_status'            => $fraudStatus,
        ]);

        // Generate PDF & kirim email hanya jika baru saja menjadi paid
        if ($newStatus === 'paid' && $wasPending) {
            $this->handlePaidRegistration($registration->fresh());
        }
    }

    /**
     * Generate PDF receipt lalu kirim email konfirmasi.
     */
    private function handlePaidRegistration(Registration $registration): void
    {
        try {
            // 1. Generate PDF
            $pdfService = app(ReceiptPdfService::class);
            $pdfService->generate($registration);

            Log::info('Receipt PDF generated for ' . $registration->midtrans_order_id);
        } catch (\Exception $e) {
            Log::error('Failed to generate PDF for ' . $registration->midtrans_order_id . ': ' . $e->getMessage());
        }

        try {
            // 2. Kirim email (fresh() agar pdf_receipt_path sudah terupdate)
            Mail::to($registration->email)->send(new RegistrationPaid($registration->fresh()));

            Log::info('Email sent to ' . $registration->email . ' for ' . $registration->midtrans_order_id);
        } catch (\Exception $e) {
            Log::error('Failed to send email for ' . $registration->midtrans_order_id . ': ' . $e->getMessage());
        }
    }
}