<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'zoho_contact_id',
        "contact_owner",
        "email",
        "first_name",
        "last_name",
        "phone",
        "created_time",
        "abcd",
        "mailing_address",
        "mailing_city",
        "mailing_state",
        "mailing_zip"
    ];
}
