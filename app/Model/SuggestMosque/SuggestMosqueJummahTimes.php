<?php

namespace App\Model\SuggestMosque;

use Illuminate\Database\Eloquent\Model;

class SuggestMosqueJummahTimes extends Model
{
    protected $table = 'suggest_mosque_jummah_times';
    protected $fillable = [
        'id_suggest_mosque','date_jummah_times','order_jummah_times','time_jummah_times'
    ];

    protected $primaryKey = 'id_jummah_times';
}
