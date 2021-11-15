<?php

namespace App\Model\Mosque;

use Illuminate\Database\Eloquent\Model;

class MosqueSalahTime extends Model
{
    //
    protected $table = 'salah_times';
    protected $fillable = [
        'id_salah_times','id_mosque','date_salah_times','type_salah_times',
        'time_salah_times'
    ];

    protected $primaryKey = 'id_salah_times';
    
    public function Mosque(){
        return $this->belongsTo('App\Model\Mosque\Mosque','id_mosque','id_mosque');
    }
}
