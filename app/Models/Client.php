<?php

namespace App\Models;

use App\Enums\UserType;
use Illuminate\Database\Eloquent\Builder;

class Client extends User
{
    protected $table = 'users';

    protected $guard_name = 'api';

    protected static function booted()
    {
        static::addGlobalScope('client', function (Builder $builder) {
            $builder->where('type', UserType::CLIENT->value);
        });
    }

}
