<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Borough extends Model
{
    protected $table = 'borough';
    protected $fillable = [
        'name_borough','latitude','longitude'
    ];

    protected $primaryKey = 'id_borough';
}
