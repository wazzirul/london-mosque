<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    protected $table = 'admin';
    protected $fillable = [
        'username','password','email','role'
    ];

    protected $primaryKey = 'id_admin';
}
