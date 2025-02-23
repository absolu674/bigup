<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class ArtistCategory extends Model
{
    /** @use HasFactory<\Database\Factories\ArtistCategoryFactory> */
    use HasFactory, SoftDeletes, Sluggable;

    protected $fillable = ['name', 'description', 'slug'];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    public function artists()
    {
        return $this->belongsToMany(
            Artist::class,
            'user_artist_categories',
            'artist_category_id',
            'user_id',
        );
    }

}
