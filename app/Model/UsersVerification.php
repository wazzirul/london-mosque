<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class UsersVerification extends Model
{
    protected $table = 'users_verification';
    protected $fillable = [
        'id_user','code_verification'
    ];

    protected $primaryKey = 'id_user_verification';
}
