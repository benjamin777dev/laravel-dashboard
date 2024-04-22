<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Groups extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "isPublic",
        "isABCD",
        "zoho_group_id",
    ];

    /* public static function getZohoContactInfo()
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
    } */
}

