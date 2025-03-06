<?php

namespace App\Repositories;

use App\Models\Client;
use App\Models\Votes;

class ClientRepository
{
    protected $client;
    /**
     * Create a new class instance.
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function index()
    {
        return $this->client->all();
    }

    public function getClientByAlias($alias)
    {
        return $this->client->where('alias', $alias)->firstOrFail();
    }

    public function votesGiven()
    {
        return $this->client->hasMany(Votes::class, 'user_id');
    }
}
