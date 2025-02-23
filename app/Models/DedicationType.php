<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DedicationType extends Model
{
    /** @use HasFactory<\Database\Factories\DedicationTypeFactory> */
    use HasFactory, Sluggable;

    protected $fillable = ['name', 'description', 'slug'];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    public function dedication()
    {
        return $this->hasMany(Dedication::class);
    }
}
