<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OfflineChatMessageMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $session;
    public $chat;

    /**
     * Create a new message instance.
     */
    public function __construct($session, $chat)
    {
        $this->session = $session;
        $this->chat = $chat;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Pesan Baru Live Chat (Admin Offline) - ' . $this->session->nama,
            replyTo: [
                new \Illuminate\Mail\Mailables\Address($this->session->email, $this->session->nama),
            ],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.offline-chat',
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
