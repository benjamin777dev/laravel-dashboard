<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deal extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'zip',
        'personal_transaction',
        'double_ended',
        'contactId',
        'address',
        'representing',
        'client_name_only',
        'commission',
        'probable_volume',
        'lender_company',
        'closing_date',
        'ownership_type',
        'needs_new_date2',
        'deal_name',
        'tm_preference',
        'tm_name',
        'stage',
        'sale_price',
        'zoho_deal_id',
        'pipeline1',
        'pipeline_probability',
        'zoho_deal_createdTime',
        'property_type',
        'city',
        'state',
        'lender_company_name',
        'client_name_primary',
        'lender_name',
        'potential_gci',
        'created_by',
        'contractId',
        'userID',
        'isDealCompleted',
        'isInZoho',
        'review_gen_opt_out',
        'commission_flat_free',
        'deadline_em_opt_out',
        'status_rpt_opt_out',
    ];

    public function userData()
    {
        return $this->belongsTo(User::class, 'userID');
    }

    public function contactName()
    {
        return $this->belongsTo(Contact::class, 'contactId');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class, 'zoho_deal_id','what_id');
    }
    /* public function contract()
    {
        return $this->belongsTo(Contract::class, 'contractId');
    } */

    /* public function tmName()
    {
        return $this->belongsTo(User::class, 'tm_name');
    } */
}
