<?php

namespace App\Model\SuggestMosque;

use Illuminate\Database\Eloquent\Model;

class SuggestMosque extends Model
{
    protected $table = 'suggest_mosque';
    protected $fillable = [
        'id_user','id_mosque','id_borough','id_district','name_mosque','address','latitude','longitude'
    ];

    protected $primaryKey = 'id_suggest_mosque';

    public function User(){
        return $this->belongsTo('App\Users','id_user','id_user');
    }

    public function Mosque(){
        return $this->belongsTo('App\Mosque','id_mosque','id_mosque');
    }

    public function Borough(){
        return $this->belongsTo('App\Borough','id_borough','id_borough');
    }

    public function District(){
        return $this->belongsTo('App\Districts','id_district','id_district');
    }
}
