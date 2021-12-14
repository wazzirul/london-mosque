<?php

namespace App\Model\Mosque;

use Illuminate\Database\Eloquent\Model;
use DB;

class Mosque extends Model
{
    //
    protected $table = 'mosque';
    protected $fillable = [
        'id_mosque_google_place','id_borough','id_district','slug_mosque',
        'name_mosque','address','latitude','longitude'
    ];

    protected $primaryKey = 'id_mosque';
    
    public function Borough(){
        return $this->belongsTo('App\Model\Borough','id_borough','id_borough');
    }
    public function Districts(){
        return $this->belongsTo('App\Model\Districts','id_district','id_district');
    }
    public function SalahTimes(){
        return $this->hasMany('App\Model\Mosque\MosqueSalahTime','id_mosque','id_mosque');
    }
    public function Review(){
        return $this->hasMany('App\Model\Mosque\MosqueReview','id_mosque','id_mosque');
    }
    public function AvgRating(){
    return $this->Review()->select('id_mosque', DB::raw('ROUND(AVG(rating),1) AS avg_rating'))->groupBy('id_mosque');
    }
}
