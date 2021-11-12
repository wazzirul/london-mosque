<?php

namespace App\Model\Mosque;

use Illuminate\Database\Eloquent\Model;

class Mosque extends Model
{
    //
    protected $table = 'mosque';
    protected $fillable = [
        'id_mosque_google_place','id_borough','id_district','name_mosque',
        'address','latitude','longitude'
    ];

    protected $primaryKey = 'id_mosque';
    
    public function Borough(){
        return $this->belongsTo('App\Model\Borough','id_borough','id_borough');
    }
    public function Districts(){
        return $this->belongsTo('App\Model\Districts','id_district','id_district');
    }
}
