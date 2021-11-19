<?php

namespace App\Model\Mosque;

use Illuminate\Database\Eloquent\Model;

class MosqueSpecialSalahTime extends Model
{
    //
    protected $table = 'mosque_special_salah_times';
    protected $fillable = [
        'id_mosque','category_special_salah','date_special_salah_times','order_special_salah_times',
        'time_special_salah_times'
    ];

    protected $primaryKey = 'id_special_salah_times';
    
    public function Mosque(){
        return $this->belongsTo('App\Model\Mosque\Mosque','id_mosque','id_mosque');
    }
}
