<?php

namespace App\Model\SuggestMosque;

use Illuminate\Database\Eloquent\Model;

class SuggestMosqueLog extends Model
{
    protected $table = 'suggest_mosque_log';
    protected $fillable = [
        'id_suggest_mosque','field_name_suggest_edit','id_admin','action','date_log'
    ];

    protected $primaryKey = 'id_suggest_mosque_log';

    public function Admin(){
        return $this->belongsTo('App\Model\Admin','id_admin','id_admin');
    }

}
