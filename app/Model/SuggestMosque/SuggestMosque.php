<?php

namespace App\Model\SuggestMosque;

use Illuminate\Database\Eloquent\Model;

class SuggestMosque extends Model
{
    protected $table = 'suggest_mosque';
    protected $fillable = [
        'id_user','id_mosque','name_mosque','name_mosque_approve_status','address',
        'address_approve_status','latitude','latitude_approve_status','longitude','longitude_approve_status'
    ];

    protected $primaryKey = 'id_suggest_mosque';

    public function User(){
        return $this->belongsTo('App\Model\Users','id_user','id_user');
    }

    public function Mosque(){
        return $this->belongsTo('App\Model\Mosque\Mosque','id_mosque','id_mosque');
    }
}
