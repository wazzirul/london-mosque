<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AuthenticationToken extends Model
{
    protected $table = 'authentication_token';
    protected $fillable = [
        'user_auth','auth_token','expired'
    ];

    protected $primaryKey = 'id';
}
