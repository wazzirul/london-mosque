<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SalahBeginningTimes extends Model
{
    //
    protected $table = 'salah_beginning_times';
    protected $fillable = [
        'id_salah_beginning_times','date_salah_beginning_times','type_salah_beginning_times',
        'time_salah_beginning_times'
    ];

    protected $primaryKey = 'id_salah_beginning_times';
}
