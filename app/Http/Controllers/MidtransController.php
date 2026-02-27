<?php

namespace App\Http\Controllers;

use App\Services\MidtransService;
use Illuminate\Http\Request;

class MidtransController extends Controller
{
    public function __construct(private MidtransService $midtrans) {}

    public function callback(Request $request)
    {
        $payload = $request->all();

        try {
            $this->midtrans->handleCallback($payload);
            return response()->json(['status' => 'ok']);
        } catch (\Exception $e) {
            \Log::error('Midtrans callback error: ' . $e->getMessage(), $payload);
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}