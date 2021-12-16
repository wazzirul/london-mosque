<?php

namespace App\Model\SuggestMosque;

use Illuminate\Database\Eloquent\Model;

class SuggestMosqueSalahTimes extends Model
{
    protected $table = 'suggest_mosque_salah_times';
    protected $fillable = [
        'id_suggest_mosque','date_salah_times','type_salah_times','time_salah_times','salah_times_approve_status'
    ];

    protected $primaryKey = 'id_suggest_salah_times';
}
