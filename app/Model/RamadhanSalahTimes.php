<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class RamadhanSalahTimes extends Model
{
    //
    protected $table = 'ramadhan_salah_times';
    protected $fillable = [
        'id_ramadhan_salah_times','date_ramadhan_salah_times','type_ramadhan_salah_times',
        'time_ramadhan_salah_times'
    ];

    protected $primaryKey = 'id_ramadhan_salah_times';
}
