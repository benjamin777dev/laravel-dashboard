<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Submittals extends Model
{
    use HasFactory;

     protected $fillable = [
        'name',
        "userId",
        "dealId",
        "zoho_submittal_id",
    ];

    public static function getZohoContactInfo()
    {
        // Retrieve Zoho contact ID, last name, and first name
        return self::select('zoho_contact_id', 'last_name', 'first_name')->get();
    }

    public function userData()
    {
        return $this->belongsTo(User::class, 'userId');
    }

    public function contactData()
    {
        return $this->belongsTo(Contact::class, 'contactId');
    }
}
