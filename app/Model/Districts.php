<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Districts extends Model
{
    protected $table = 'districts';
    protected $fillable = [
        'id_borough','name_district','latitude','longitude'
    ];

    protected $primaryKey = 'id_district';
}
