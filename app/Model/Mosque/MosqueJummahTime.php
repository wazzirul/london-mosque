<?php

namespace App\Model\Mosque;

use Illuminate\Database\Eloquent\Model;

class MosqueJummahTime extends Model
{
    //
    protected $table = 'mosque_jummah_times';
    protected $fillable = [
        'id_jummah_times','id_mosque','date_jummah_times','order_jummah_times',
        'time_jummah_times'
    ];

    protected $primaryKey = 'id_jummah_times';
    
    public function Mosque(){
        return $this->belongsTo('App\Model\Mosque\Mosque','id_mosque','id_mosque');
    }
}
