<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConnectionProvider extends Model
{
    protected $casts = [
        'name' => \App\Enums\LoginProvider::class,
    ];
}
