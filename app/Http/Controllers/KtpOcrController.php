<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class KtpOcrController extends Controller
{
    private string $ocrUrl = 'https://awesome-linearly-leandra.ngrok-free.dev/ocr/ktp';

    public function scan(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        try {
            $file = $request->file('image');

            $response = Http::timeout(60)
                ->withHeaders([
                    'ngrok-skip-browser-warning' => 'true',
                    'Accept' => 'application/json',
                ])
                ->attach(
                    'image',
                    file_get_contents($file->getRealPath()),
                    'ktp.' . $file->extension()
                )
                ->post($this->ocrUrl);

            if (!$response->successful()) {
                return response()->json([
                    'success' => false,
                    'message' => 'OCR service error (HTTP ' . $response->status() . '). Coba lagi.',
                ], 500);
            }

            return response()->json($response->json());

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak bisa konek ke OCR service. Pastikan Python API dan ngrok sedang berjalan.',
            ], 503);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }
}