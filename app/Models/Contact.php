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
        "business_name",
        "business_information",
        "secondory_email",
        "relationship_type",
        "market_area",
        "envelope_salutation",
        "mobile",
        "created_time",
        "abcd",
        "mailing_address",
        "mailing_city",
        "mailing_state",
        "mailing_zip",
        "isContactCompleted",
        "isInZoho",
        "Lead_Source",
        "group_id",
        "referred_id",
        "lead_source_detail",
        "spouse_partner",
        "last_called",
        "last_emailed",


    ];

    public static function getZohoContactInfo()
    {
        // Retrieve Zoho contact ID, last name, and first name
        return self::select('zoho_contact_id', 'last_name', 'first_name')->get();
    }

    public function userData()
    {
        return $this->belongsTo(User::class, 'contact_owner');
    }

    public function contactName()
    {
        return $this->belongsTo(Contact::class, 'contactId');
    }
}
