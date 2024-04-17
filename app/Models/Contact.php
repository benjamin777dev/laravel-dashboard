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

    public static function getZohoContactInfo()
    {
        // Retrieve Zoho contact ID, last name, and first name
        return self::select('zoho_contact_id', 'last_name', 'first_name')->get();
    }
}
