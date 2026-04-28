<?php

namespace App\Mail;

use App\Models\Registration;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RegistrationPending extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Registration $registration) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '💳 Selesaikan Pembayaran — Bayan Open 2026 | ' . $this->registration->uuid,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.approved', // gunakan template yang sudah ada di dokumen 2
        );
    }

    public function attachments(): array
    {
        return [];
    }
}