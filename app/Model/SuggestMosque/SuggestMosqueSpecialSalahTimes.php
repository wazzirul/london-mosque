<?php

namespace App\Model\SuggestMosque;

use Illuminate\Database\Eloquent\Model;

class SuggestMosqueSpecialSalahTimes extends Model
{
    protected $table = 'suggest_mosque_special_salah_times';
    protected $fillable = [
        'id_suggest_mosque','category_special_salah','date_special_salah_times','order_special_salah_times','time_special_salah_times'
    ];

    protected $primaryKey = 'id_special_salah_times';
}
