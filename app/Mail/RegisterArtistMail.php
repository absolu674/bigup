<?php

namespace App\Mail;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RegisterArtistMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $artist;
    /**
     * Create a new message instance.
     */
    public function __construct(User $artist)
    {
        $this->artist = $artist;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $admins = Admin::get()->pluck('email');
        return new Envelope(
            to: [
                ...$admins
            ],
            subject: 'New artist registration',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'mail.register.artist',
            with: [
                'artist' => $this->artist,
                'url' => route('artist.show', ['artist' => $this->artist->alias])
            ]
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
