<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NonTm extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        "closed_date",
        "userId",
        "dealId",
        "zoho_nontm_id",
        "isNonTmCompleted"
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

    public function dealData()
    {
        return $this->belongsTo(Deal::class, 'dealId','zoho_deal_id');
    }

    public function contactData()
    {
        return $this->belongsTo(Contact::class, 'contactId');
    }
    
}
