<?php

namespace App\Model\SuggestMosque;

use Illuminate\Database\Eloquent\Model;

class SuggestMosqueSalahTimes extends Model
{
    protected $table = 'suggest_mosque_salah_times';
    protected $fillable = [
        'id_suggest_mosque','date_salah_times','action','soft_delete'
    ];

    protected $primaryKey = 'id_suggest_salah_times';
}
