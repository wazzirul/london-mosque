<?php

namespace App\Model\Mosque;

use Illuminate\Database\Eloquent\Model;

class MosqueFacilities extends Model
{
    //
    protected $table = 'mosque_facilities';
    protected $fillable = [
        'id_mosque_facilities','id_mosque','id_facilities'
    ];

    protected $primaryKey = 'id_mosque_facilities';
    
    public function Mosque(){
        return $this->belongsTo('App\Model\Mosque\Mosque','id_mosque','id_mosque');
    }
    public function Facilities(){
        return $this->belongsTo('App\Model\Facilities','id_facilities','id_facilities');
    }
}
