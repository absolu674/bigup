<?php

namespace App\Repositories;

use App\Models\Artist;
use App\Models\ArtistCategory;
use App\Models\Votes;

class ArtistRepository
{
    protected $artist;
    protected $categoryRepository;
    /**
     * Create a new class instance.
     */
    public function __construct(Artist $artist, CategoryRepository $categoryRepository)
    {
        $this->artist = $artist;
        $this->categoryRepository = $categoryRepository;
    }

    public function index()
    {
        return $this->artist->all();
    }

    public function getArtistById($id)
    {
        return $this->artist->find($id);
    }

    public function getArtistByAlias($alias)
    {
        return $this->artist->where('alias', $alias)->firstOrFail();
    }

    public function votesReceived()
    {
        return $this->artist->hasMany(Votes::class, 'artist_id');
    }

    public function categories()
    {
        return $this->artist->belongsToMany(ArtistCategory::class, 'artist_categories', 'artist_id', 'category_id');
    }

}
