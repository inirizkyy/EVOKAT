<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MemberRejectedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $pemohon;
    public $permohonan;
    public $rejectedList;

    /**
     * Create a new message instance.
     */
    public function __construct($pemohon, $permohonan, $rejectedList)
    {
        $this->pemohon = $pemohon;
        $this->permohonan = $permohonan;
        $this->rejectedList = $rejectedList;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Pemberitahuan Berkas Ditolak - Nomor Registrasi: ' . $this->permohonan->nomor_permohonan,
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
            view: 'emails.member-rejected',
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
