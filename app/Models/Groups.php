<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Groups extends Model
{
    use HasFactory;

    protected $fillable = [
        "ownerId",
        "name",
        "isPublic",
        "isABCD",
        "zoho_group_id",
        "isShow"
    ];

    public function ownerData()
    {
        return $this->belongsTo(User::class, 'ownerId');
    }

    /* public static function getZohoContactInfo()
    {
        // Retrieve Zoho contact ID, last name, and first name
        return self::select('zoho_contact_id', 'last_name', 'first_name')->get();
    }

    

    public function contactName()
    {
        return $this->belongsTo(Contact::class, 'contactId');
    } */
}

