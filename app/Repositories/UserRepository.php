<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    public function index()
    {
        return User::all();
    }
}
