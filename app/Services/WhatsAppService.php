<?php

namespace App\Services;

use App\Models\Registration;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected string $appId;
    protected string $secretKey;
    protected string $channelId;
    protected string $language;
    protected array  $templates;

    public function __construct()
    {
        $this->appId     = config('services.qiscus.app_id', '');
        $this->secretKey = config('services.qiscus.secret_key', '');
        $this->channelId = config('services.qiscus.channel_id', '');
        $this->language  = config('services.qiscus.language', 'id');
        $this->templates = config('services.qiscus.templates', []);
    }

    protected function endpoint(): string
    {
        return "https://omnichannel.qiscus.com/whatsapp/v1/{$this->appId}/{$this->channelId}/messages";
    }

    public function sendPaymentLink(Registration $registration): bool
    {
        $paymentLink = $registration->paymentLink();
        $expiresAt   = optional($registration->payment_token_expires_at)
                        ->format('d M Y, H:i') ?? '-';

        return $this->sendTemplate($registration, 'payment_link', [
            $registration->nama,
            $registration->tim_pb,
            $registration->kategori_label,
            $registration->harga_formatted,
            $paymentLink,
            $expiresAt,
        ]);
    }

    public function sendReminder(Registration $registration): bool
    {
        return $this->sendTemplate($registration, 'reminder', [
            $registration->nama,
            $registration->paymentLink(),
        ]);
    }

    public function notifyAdminPaymentUploaded(Registration $registration): void
    {
        $admins = [
            '6285377640809',
            '628125431744',
            '6282133212777',
        ];

        foreach ($admins as $admin) {
            $this->sendTemplateToNumber($admin, 'admin_notification', [
                $registration->nama,
                $registration->tim_pb,
                $registration->kategori_label,
                $registration->uuid,
            ]);
        }
    }

    public function sendPaymentSuccess(Registration $registration): bool
    {
        return $this->sendTemplate($registration, 'payment_success', [
            $registration->nama,
            $registration->tim_pb,
            $registration->kategori_label,
            $registration->harga_formatted,
        ]);
    }

    public function sendPaymentRejected(Registration $registration): bool
    {
        return $this->sendTemplate($registration, 'rejected', [
            $registration->nama,
            $registration->paymentLink(),
        ]);
    }

    public function sendTemplateToNumber(string $phone, string $templateKey, array $params): bool
    {
        $to = $this->normalizePhoneNumber($phone);

        if (! $to) {
            Log::warning('[WhatsApp] Nomor tidak valid', ['phone' => $phone]);
            return false;
        }

        if (empty($this->appId) || empty($this->secretKey) || empty($this->channelId)) {
            Log::warning('[WhatsApp] Konfigurasi Qiscus belum lengkap (app_id/secret_key/channel_id).');
            return false;
        }

        $templateName = $this->templates[$templateKey] ?? null;
        if (! $templateName) {
            Log::warning('[WhatsApp] Template tidak ditemukan: ' . $templateKey);
            return false;
        }

        $bodyParameters = array_map(
            fn ($value) => ['type' => 'text', 'text' => (string) $value],
            $params
        );

        $payload = [
            'to'   => $to,
            'type' => 'template',
            'template' => [
                'namespace' => '',
                'name'      => $templateName,
                'language'  => [
                    'policy' => 'deterministic',
                    'code'   => $this->language,
                ],
                'components' => [
                    [
                        'type'       => 'body',
                        'parameters' => $bodyParameters,
                    ],
                ],
            ],
        ];

        try {
            $response = Http::timeout(15)
                ->withHeaders([
                    'Qiscus-App-Id'     => $this->appId,
                    'Qiscus-Secret-Key' => $this->secretKey,
                    'Content-Type'      => 'application/json',
                ])
                ->post($this->endpoint(), $payload);

            if (! $response->successful()) {
                Log::error('[WhatsApp] API error', [
                    'status'   => $response->status(),
                    'body'     => $response->body(),
                    'template' => $templateName,
                    'to'       => $to,
                ]);
                return false;
            }

            Log::info('[WhatsApp] Terkirim', [
                'to'       => $to,
                'template' => $templateName,
                'response' => $response->json(),
            ]);

            return true;

        } catch (\Throwable $e) {
            Log::error('[WhatsApp] Exception: ' . $e->getMessage(), [
                'to'       => $to,
                'template' => $templateName,
            ]);
            return false;
        }
    }

    protected function sendTextMessage(string $to, string $body): void
    {
        if (! $to) {
            Log::warning('[WhatsApp] Nomor admin tidak valid', ['to' => $to]);
            return;
        }

        if (empty($this->appId) || empty($this->secretKey) || empty($this->channelId)) {
            Log::warning('[WhatsApp] Konfigurasi Qiscus belum lengkap (app_id/secret_key/channel_id).');
            return;
        }

        $payload = [
            'to'   => $to,
            'type' => 'text',
            'text' => [
                'body' => $body,
            ],
        ];

        try {
            $response = Http::timeout(15)
                ->withHeaders([
                    'Qiscus-App-Id'     => $this->appId,
                    'Qiscus-Secret-Key' => $this->secretKey,
                    'Content-Type'      => 'application/json',
                ])
                ->post($this->endpoint(), $payload);

            if (! $response->successful()) {
                Log::error('[WhatsApp] API error (admin notification)', [
                    'status' => $response->status(),
                    'body'   => $response->body(),
                    'to'     => $to,
                ]);
            } else {
                Log::info('[WhatsApp] Admin notification sent', ['to' => $to]);
            }
        } catch (\Throwable $e) {
            Log::error('[WhatsApp] Exception sending admin notification: ' . $e->getMessage(), [
                'to' => $to,
            ]);
        }
    }

    protected function sendTemplate(Registration $registration, string $templateKey, array $params): bool
    {
        return $this->sendTemplateToNumber($registration->no_hp, $templateKey, $params);
    }

    protected function normalizePhoneNumber(string $phone): ?string
    {
        $digits = preg_replace('/[^0-9]/', '', $phone);

        if (empty($digits)) return null;

        if (str_starts_with($digits, '62'))  return $digits;
        if (str_starts_with($digits, '0'))   return '62' . substr($digits, 1);
        if (str_starts_with($digits, '8'))   return '62' . $digits;

        return null;
    }
}