<?php

namespace App\Repositories;

use App\Models\Artist;
use App\Models\ArtistCategory;

class CategoryRepository
{
    protected $category;
    protected $categoryRepository;
    /**
     * Create a new class instance.
     */
    public function __construct(ArtistCategory $category)
    {
        $this->category = $category;
    }
    public function index()
    {
        return ArtistCategory::all();
    }

    public function store(array $data)
    {
        return ArtistCategory::create($data);
    }

    public function show($id)
    {
        return ArtistCategory::findOrFail($id);
    }

    public function update($id, array $data)
    {
        $category = ArtistCategory::findOrFail($id);
        $category->update($data);
        return $category;
    }

    public function destroy($id)
    {
        $category = ArtistCategory::findOrFail($id);
        $category->delete();
        return $category;
    }    

    public function getCategoryByAlias($alias): ArtistCategory
    {
        return ArtistCategory::where('alias', $alias)->first();
    }

    public function getAllArtistByCategry()
    {
        return $this->category::with('artists')->get();
    }

    public function getArtistByCategry($slug)
    {
        return $this->category::where('slug', $slug)->with('artists')->get();
    }
}
