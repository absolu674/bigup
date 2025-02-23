<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DedicationVideo extends Model
{
    protected $fillable = [
        'dedication_id',
        'fichier_id',
    ];

    // public function dedication()
    // {
    //     return $this->belongsTo(Dedication::class);
    // }

    // public function fichier()
    // {
    //     return $this->belongsTo(Fichier::class);
    // }
    
}
