<?php

namespace App\Jobs;

use App\Mail\RegistrationPaid;
use App\Models\Registration;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class ProcessPaidRegistration implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public Registration $registration) {}

    public function handle(): void
    {
        // Generate PDF
        $pdf  = Pdf::loadView('pdf.receipt', ['registration' => $this->registration]);
        $path = 'receipts/' . $this->registration->uuid . '.pdf';

        Storage::put($path, $pdf->output());

        $this->registration->update(['pdf_receipt_path' => $path]);

        // Send Email (if email exists)
        if ($this->registration->email) {
            Mail::to($this->registration->email)
                ->send(new RegistrationPaid($this->registration));
        }
    }
}