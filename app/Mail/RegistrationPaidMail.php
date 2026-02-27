<?php

namespace App\Mail;

use App\Models\Registration;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RegistrationPaidMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Registration $registration) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Konfirmasi Pembayaran Bayan Open 2026 - ' . $this->registration->midtrans_order_id,
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
        $path = storage_path('app/' . $this->registration->pdf_receipt_path);

        if (file_exists($path)) {
            return [
                \Illuminate\Mail\Mailables\Attachment::fromPath($path)
                    ->as('receipt-' . $this->registration->midtrans_order_id . '.pdf')
                    ->withMime('application/pdf'),
            ];
        }

        return [];
    }
}