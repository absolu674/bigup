<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fichier extends Model
{
    protected $fillable = [
        'name',
        'path',
        'type',
        'mime_type',
        'size',
        'extension',
    ];

}
