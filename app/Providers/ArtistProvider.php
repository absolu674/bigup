<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ArtistProvider extends ServiceProvider
{
    protected $subscribe = [
        EventSuscriber::class
    ];
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }

    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
