<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Donations extends Model
{
    protected $table = 'donations';
    protected $fillable = [
        'id_user_donation','amount_donation','date_donation','time_donation'
    ];

    protected $primaryKey = 'id_donation';
}
