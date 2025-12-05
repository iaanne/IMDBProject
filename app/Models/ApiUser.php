<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiUser extends Authenticatable
{
    use HasApiTokens;

    protected $fillable = [
        'username',
        'password',
        'role'
    ];

    protected $hidden = ['password'];
}

