<?php

namespace App\Model\Mosque;

use Illuminate\Database\Eloquent\Model;

class MosqueImage extends Model
{
    //
    protected $table = 'mosque_image';
    protected $fillable = [
        'id_mosque','public_id','mosque_image_url'
    ];

    protected $primaryKey = 'id_mosque_image';
    
    public function Mosque(){
        return $this->belongsTo('App\Model\Mosque\Mosque','id_mosque','id_mosque');
    }
}
