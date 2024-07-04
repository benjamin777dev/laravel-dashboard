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
        "email",
        "Commission",
        "referral_fee_paid_out",
        "home_warranty_paid_out_agent",
        "any_additional_fees_charged",
        "final_purchase_price",
        "amount_to_chr_gives",
        "agent_comments",
        "other_commission_notes",
        "zoho_nontm_id",
        "isNonTmCompleted",
        'referralFeeAmount',
        'referralFeeBrokerage',
        'referralAgreement',
        'hasW9Provided',
        'homeWarrentyAmount',
        'homeWarrentyDescription',
        'additionalFeesAmount',
        'additionalFeesDescription'
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
