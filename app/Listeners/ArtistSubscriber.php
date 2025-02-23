<?php

namespace App\Listeners;

use App\Events\RegisterArtistEvent;
use App\Mail\RegisterArtistMail;
use Illuminate\Mail\Mailer;
use Illuminate\Support\Facades\Log;

class ArtistSubscriber
{
    /**
     * Create the event listener.
     */
    public function __construct(private Mailer $mailer)
    {
        //
    }

    public function handleArtistCreated(RegisterArtistEvent $event)
    {
        $this->mailer->send(new RegisterArtistMail($event->user));
    }

    public function subscribe(): array
    {
        return [
            RegisterArtistEvent::class => 'handleArtistCreated'
        ];
    }
}
