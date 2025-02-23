<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Votes extends Model
{
    /** @use HasFactory<\Database\Factories\VotesFactory> */
    use HasFactory, Sluggable;

    protected $fillable = ['user_id', 'artist_id'];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => ['user.firstname', 'artist.firstname']
            ]
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function artist()
    {
        return $this->belongsTo(User::class, 'artist_id');
    }

}
