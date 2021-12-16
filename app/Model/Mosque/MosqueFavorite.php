<?php

namespace App\Model\Mosque;

use Illuminate\Database\Eloquent\Model;

class MosqueFavorite extends Model
{
    //
    protected $table = 'mosque_favorite';
    protected $fillable = [
        'id_mosque','id_user','status_mosque_favorite'
    ];

    protected $primaryKey = 'id_mosque_favorite';
    
    public function Mosque(){
        return $this->belongsTo('App\Model\Mosque\Mosque','id_mosque','id_mosque');
    }
    public function User(){
        return $this->belongsTo('App\Model\Users','id_user','id_user');
    }
}
