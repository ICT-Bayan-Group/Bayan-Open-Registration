<?php

namespace App\Services;

use App\Models\Registration;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class ReceiptPdfService
{
    public function generate(Registration $registration): string
    {
        // Generate PDF dari view
        $pdf = Pdf::loadView('pdf.receipt', compact('registration'))
            ->setPaper('a4', 'portrait');

        // Simpan ke storage/app/receipts/
        $filename = 'receipts/receipt-' . $registration->midtrans_order_id . '.pdf';

        Storage::disk('local')->put($filename, $pdf->output());

        // Update path di database
        $registration->update(['pdf_receipt_path' => $filename]);

        return $filename;
    }
}