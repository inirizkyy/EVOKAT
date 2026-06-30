<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PermohonanDiajukanMail extends Mailable
{
    use Queueable, SerializesModels;

    public $permohonan;

    /**
     * Create a new message instance.
     */
    public function __construct($permohonan)
    {
        $this->permohonan = $permohonan;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Pendaftaran Permohonan Sumpah Advokat - Nomor Registrasi: ' . $this->permohonan->nomor_permohonan,
            replyTo: [
                new \Illuminate\Mail\Mailables\Address('adminadvokat@gmail.com', 'Admin EVOKAT'),
            ],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            text: 'emails.permohonan-diajukan-text',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
