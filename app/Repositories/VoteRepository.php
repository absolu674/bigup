<?php

namespace App\Repositories;

use App\Models\Votes;

class VoteRepository
{

    public function create($artist)
    {
        $vote = Votes::create([
            'user_id' => auth()->user()->id(),
            'artist_id' => $artist->id,
        ]);

        return $vote;
    }

    public function getAllVotes()
    {
        return Votes::all();
    }
}
