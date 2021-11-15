<?php

namespace App\Model\SuggestMosque;

use Illuminate\Database\Eloquent\Model;

class SuggestMosqueLog extends Model
{
    protected $table = 'suggest_mosque_log';
    protected $fillable = [
        'id_suggest_mosque','id_admin','action','soft_delete'
    ];

    protected $primaryKey = 'id_suggest_mosque_log';
}
