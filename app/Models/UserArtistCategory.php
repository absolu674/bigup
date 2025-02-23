<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserArtistCategory extends Model
{
    /** @use HasFactory<\Database\Factories\UserArtistCategoryFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'artist_category_id',
    ];
}
