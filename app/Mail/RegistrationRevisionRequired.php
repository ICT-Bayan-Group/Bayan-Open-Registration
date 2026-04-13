<?php

namespace App\Mail;

use App\Models\Registration;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RegistrationRevisionRequired extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly Registration $registration
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '⚠ Revisi Diperlukan — Pendaftaran Tim ' . $this->registration->tim_pb . ' | Bayan Open 2026',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.registration-revision',
            with: [
                'registration' => $this->registration,
                'revisionUrl'  => route('registration.revisi', [
                    'token' => $this->registration->revision_token,
                ]),
                'expiresAt'    => $this->registration->revision_token_expires_at,
            ],
        );
    }
}