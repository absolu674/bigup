<?php

namespace App\Http\Controllers;

use App\Classes\ApiResponseClass;
use App\Repositories\ArtistRepository;
use App\Repositories\VoteRepository;
use Illuminate\Http\Request;

class VoteController extends Controller
{
    private $voteRepository;
    private $artistRespository;

    public function __construct(VoteRepository $voteRepository, ArtistRepository $artistRespository)
    {
        $this->voteRepository = $voteRepository;
        $this->artistRespository = $artistRespository;
    }

    public function create(Request $request)
    {
        $artist = $this->artistRespository->getArtistByAlias($request->alias);
        $vote = $this->voteRepository->create($artist);

        return ApiResponseClass::sendResponse(
            $vote,
            'Vote created successfully',
            201
        );
    }

    public function index()
    {
        $votes = $this->voteRepository->getAllVotes();

        return ApiResponseClass::sendResponse(
            $votes,
            'Votes retrieved successfully',
            200
        );
    }

    public function getVotesByArtist($alias)
    {
        $artist = $this->artistRespository->getArtistByAlias($alias);
        $votes = $artist->votesReceived;

        return ApiResponseClass::sendResponse(
            $votes,
            'Votes retrieved successfully for the artist',
            200
        );
    }
}
