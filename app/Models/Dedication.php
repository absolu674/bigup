<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dedication extends Model
{
    /** @use HasFactory<\Database\Factories\DedicationFactory> */
    use HasFactory, Sluggable;

    protected $fillable = [
        'dedication_type_id', 
        'user_id', 
        'artist_id', 
        'message',
        'message_rejected',
        'firstname', 
        'lastname', 
        'email', 
        'phone_payment', 
        'is_self',
        'state'
    ];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => ['user.firstname', 'artist.firstname', 'dedication_types.name']
            ]
        ];
    }

    public function dedicationType()
    {
        return $this->belongsTo(DedicationType::class);
    }

    public function user()
    {
        return $this->belongsTo(Client::class);
    }

    public function artist()
    {
        return $this->belongsTo(Artist::class);
    }
    
    public function videoFile()
    {
        return $this->hasOneThrough(
            Fichier::class,
            DedicationVideo::class,
            'dedication_id',
            'id',
            'id',
        );
    }
    
}
