<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $codigo;
    public string $nombre;

    public function __construct(string $codigo, string $nombre)
    {
        $this->codigo = $codigo;
        $this->nombre = $nombre;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Código de verificación — Vaso de Leche Cusco',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.otp',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
