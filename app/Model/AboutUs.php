<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AboutUs extends Model
{
    //
    protected $table = 'about_us';
    protected $fillable = [
        'about_us'
    ];

    protected $primaryKey = 'id_about_us';
}
