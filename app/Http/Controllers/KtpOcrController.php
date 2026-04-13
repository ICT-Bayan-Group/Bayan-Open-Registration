<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class KtpOcrController extends Controller
{
    private string $ocrUrl = 'http://192.168.0.21:9000/ocr/ktp';

    /**
     * Kata kunci kota valid — case-insensitive, harus mengandung salah satu.
     */
    private const VALID_CITY_KEYWORDS = [
        'BALIKPAPAN',
    ];

    // ================================================================
    // POST /ocr/ktp
    // ================================================================

    public function scan(Request $request): JsonResponse
    {
        $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $file = $request->file('image');

        try {
            $response = Http::timeout(60)
                ->withHeaders([
                    'ngrok-skip-browser-warning' => 'true',
                    'Accept'                     => 'application/json',
                ])
                ->attach(
                    'image',
                    file_get_contents($file->getRealPath()),
                    'ktp.' . $file->extension()
                )
                ->post($this->ocrUrl);

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::warning('[KTP-OCR] Connection failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Tidak bisa konek ke OCR service. Pastikan Python API dan ngrok sedang berjalan.',
            ], 503);
        } catch (\Exception $e) {
            Log::error('[KTP-OCR] Unexpected error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }

        // ── HTTP error dari OCR service ────────────────────────────
        if (! $response->successful()) {
            Log::warning('[KTP-OCR] OCR service HTTP ' . $response->status());
            return response()->json([
                'success' => false,
                'message' => 'OCR service error (HTTP ' . $response->status() . '). Coba lagi.',
            ], 500);
        }

        // ── Parse response dari Python OCR ─────────────────────────
        $ocrResult = $response->json();

        if (! is_array($ocrResult) || empty($ocrResult['success'])) {
            return response()->json([
                'success' => false,
                'message' => $ocrResult['message'] ?? 'OCR gagal membaca KTP. Coba foto ulang lebih jelas.',
            ], 422);
        }

        // ── Normalize & validasi kota ──────────────────────────────
        $data           = $ocrResult['data'] ?? $ocrResult;
        $kotaRaw        = strtoupper(trim($data['kota'] ?? $data['kabupaten_kota'] ?? ''));
        $cityValid      = $this->isCityValid($kotaRaw);

        // ── Normalize jenis_kelamin → "L" atau "P" ────────────────
        $jenisKelaminRaw  = strtoupper(trim($data['jenis_kelamin'] ?? ''));
        $jenisKelaminNorm = $this->normalizeJenisKelamin($jenisKelaminRaw);

        // ── Build response terstandarisasi ─────────────────────────
        return response()->json([
            'success' => true,
            'data'    => [
                'nik'               => trim($data['nik']           ?? ''),
                'nama'              => trim($data['nama']          ?? ''),
                'tanggal_lahir'     => $this->normalizeTanggal($data['tanggal_lahir'] ?? $data['tgl_lahir'] ?? ''),
                'kota'              => $kotaRaw,
                'city_valid'        => $cityValid,
                'city_raw'          => $kotaRaw,

                // field tambahan dari OCR (untuk info admin & validasi kategori)
                'tempat_lahir'      => trim($data['tempat_lahir']  ?? ''),
                'jenis_kelamin'     => $jenisKelaminNorm,       // "L" | "P" | ""
                'jenis_kelamin_raw' => $jenisKelaminRaw,        // nilai mentah dari OCR
                'agama'             => trim($data['agama']         ?? ''),
                'pekerjaan'         => trim($data['pekerjaan']     ?? ''),
            ],
        ]);
    }

    // ================================================================
    // Validasi kota — harus mengandung "BALIKPAPAN"
    // ================================================================

    private function isCityValid(string $city): bool
    {
        if (empty($city)) return false;

        $city = strtoupper(trim($city));
        foreach (self::VALID_CITY_KEYWORDS as $keyword) {
            if (str_contains($city, $keyword)) {
                return true;
            }
        }
        return false;
    }

    // ================================================================
    // Normalize jenis_kelamin → "L" atau "P"
    // Menangani berbagai format OCR: LAKI-LAKI, PEREMPUAN, L, P, dsb.
    // ================================================================

    private function normalizeJenisKelamin(string $raw): string
    {
        $raw = strtoupper(trim($raw));

        if (empty($raw)) return '';

        // Cek PEREMPUAN / WANITA / P / PR terlebih dahulu
        if (in_array($raw, ['P', 'PR', 'WANITA', 'PEREMPUAN'], true)) {
            return 'P';
        }
        if (str_contains($raw, 'PEREMPUAN') || str_contains($raw, 'WANITA')) {
            return 'P';
        }

        // Cek LAKI-LAKI / PRIA / L / LK
        if (in_array($raw, ['L', 'LK', 'PRIA', 'LAKI', 'LAKI-LAKI'], true)) {
            return 'L';
        }
        if (str_contains($raw, 'LAKI') || str_contains($raw, 'PRIA')) {
            return 'L';
        }

        return ''; // tidak dikenali
    }

    // ================================================================
    // Normalize tanggal → DD-MM-YYYY
    // Support: DD-MM-YYYY, DD/MM/YYYY, YYYY-MM-DD
    // ================================================================

    private function normalizeTanggal(string $raw): string
    {
        $raw = trim($raw);
        if (empty($raw)) return '';

        // Sudah format DD-MM-YYYY
        if (preg_match('/^\d{2}-\d{2}-\d{4}$/', $raw)) return $raw;

        // DD/MM/YYYY
        if (preg_match('/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/', $raw, $m)) {
            return sprintf('%02d-%02d-%04d', $m[1], $m[2], $m[3]);
        }

        // YYYY-MM-DD
        if (preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $raw, $m)) {
            return sprintf('%02d-%02d-%04d', $m[3], $m[2], $m[1]);
        }

        return $raw;
    }
}