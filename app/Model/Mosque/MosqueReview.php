<?php

namespace App\Model\Mosque;

use Illuminate\Database\Eloquent\Model;

class MosqueReview extends Model
{
    //
    protected $table = 'mosque_review';
    protected $fillable = [
        'id_mosque','id_user','rating','review'
    ];

    protected $primaryKey = 'id_mosque_review';
    
    public function Mosque(){
        return $this->belongsTo('App\Model\Mosque\Mosque','id_mosque','id_mosque');
    }
    public function User(){
        return $this->belongsTo('App\Model\Users','id_user','id_user');
    }
}
