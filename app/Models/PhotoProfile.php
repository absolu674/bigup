<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhotoProfile extends Model
{
    protected $fillable = [
        'user_id',
        'fichier_id',
    ];

    public function fichier()
    {
        return $this->belongsTo(Fichier::class, 'fichier_id');
    }
}
