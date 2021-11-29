<?php

namespace App\Model\SuggestMosque;

use Illuminate\Database\Eloquent\Model;

class SuggestMosqueImage extends Model
{
    protected $table = 'suggest_mosque_image';
    protected $fillable = [
        'id_suggest_mosque','mosque_image_url','mosque_image_approve_status'
    ];

    protected $primaryKey = 'id_suggest_mosque_image';
}
