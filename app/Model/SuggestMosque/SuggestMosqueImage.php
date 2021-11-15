<?php

namespace App\Model\SuggestMosque;

use Illuminate\Database\Eloquent\Model;

class SuggestMosqueImage extends Model
{
    protected $table = 'suggest_mosque_image';
    protected $fillable = [
        'id_suggest_mosque','mosque_image_url'
    ];

    protected $primaryKey = 'id_suggest_mosque_image';
}
