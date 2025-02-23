<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'firstname',
        'lastname',
        'password',
        'gender',
        'type',
        'email',
        'phone',
        'professional_phone',
        'dedication_price',
        'country',
        'bio'
    ];

    protected static function boot() {
        parent::boot();

        static::creating(function ($user) {
            $user->alias = $user->alias == null ? Str::random(16) : $user->alias;
        });
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'gender' => \App\Enums\Gender::class,
        ];
    }    

    public function artistCategories()
    {
        return $this->belongsToMany(
            ArtistCategory::class,
            'user_artist_categories', // Nom correct de la table pivot
            'user_id', // Clé étrangère de User
            'artist_category_id' // Clé étrangère de ArtistCategory
        );
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function dedications()
    {
        return $this->hasMany(Dedication::class);
    }
    
}
