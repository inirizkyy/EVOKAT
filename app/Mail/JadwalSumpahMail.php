<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class JadwalSumpahMail extends Mailable
{
    use Queueable, SerializesModels;

    public $jadwal;

    /**
     * Create a new message instance.
     */
    public function __construct($jadwal)
    {
        $this->jadwal = $jadwal;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Pemberitahuan Jadwal Pelaksanaan Sumpah Advokat',
            replyTo: [
                new \Illuminate\Mail\Mailables\Address('adminadvokat@gmail.com', 'Admin EVOKAT'),
            ],
        );
    }

    public function content(): Content
    {
        return new Content(
            text: 'emails.jadwal-sumpah-text',
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
