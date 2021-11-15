<?php

namespace App\Model\SuggestMosque;

use Illuminate\Database\Eloquent\Model;

class SuggestMosqueFacilities extends Model
{
    protected $table = 'suggest_mosque_facilities';
    protected $fillable = [
        'id_suggest_mosque','id_facilities'
    ];

    protected $primaryKey = 'id_suggest_mosque_facilities';
}
