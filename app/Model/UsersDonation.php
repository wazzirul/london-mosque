<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class UsersDonation extends Model
{
    protected $table = 'users_donation';
    protected $fillable = [
        'id_user','type_donation','repeated_donation','anonymous_donation','first_name','last_name',
        'email_address','mobile_number','card_name','cvc','cardholder_name','exp'
    ];

    protected $primaryKey = 'id_user_donation';
}
