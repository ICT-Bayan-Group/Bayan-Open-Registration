<?php

namespace App\Mail;

use App\Models\Registration;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class RegistrationPaid extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Registration $registration) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '✅ Pembayaran Berhasil — Bayan Open 2026 | ' . $this->registration->midtrans_order_id,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.paid',
        );
    }

    public function attachments(): array
    {
        $attachments = [];

        if ($this->registration->pdf_receipt_path) {
            $path = Storage::disk('local')->path($this->registration->pdf_receipt_path);

            if (file_exists($path)) {
                $attachments[] = Attachment::fromPath($path)
                    ->as('Receipt-' . $this->registration->midtrans_order_id . '.pdf')
                    ->withMime('application/pdf');
            }
        }

        return $attachments;
    }
}