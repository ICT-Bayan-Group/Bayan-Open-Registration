<?php

namespace App\Services;

use App\Models\Registration;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;

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
    // Verifikasi signature key
    $serverKey    = config('midtrans.server_key');
    $orderId      = $payload['order_id'];
    $statusCode   = $payload['status_code'];
    $grossAmount  = $payload['gross_amount'];
    $signatureKey = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

    if ($signatureKey !== $payload['signature_key']) {
        throw new \Exception('Invalid signature');
    }

    $registration = \App\Models\Registration::where('midtrans_order_id', $orderId)->firstOrFail();

    $transactionStatus = $payload['transaction_status'];
    $fraudStatus       = $payload['fraud_status'] ?? null;

    // Tentukan status akhir
    $status = match (true) {
        $transactionStatus === 'capture' && $fraudStatus === 'accept' => 'paid',
        $transactionStatus === 'settlement'                           => 'paid',
        in_array($transactionStatus, ['cancel', 'deny', 'expire'])    => 'expired',
        $transactionStatus === 'pending'                              => 'pending',
        default                                                       => $registration->status,
    };

    $registration->update([
        'status'                  => $status,
        'midtrans_transaction_id' => $payload['transaction_id'] ?? null,
        'payment_type'            => $payload['payment_type']   ?? null,
        'payment_time'            => $payload['settlement_time'] ?? $payload['transaction_time'] ?? null,
        'fraud_status'            => $fraudStatus,
    ]);
}
}