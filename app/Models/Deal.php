<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deal extends Model
{
    use HasFactory;

    protected $fillable = [
        'address',
        'amount',
        'brokermint_id',
        'campaign_source',
        'cda_notes',
        'check_received',
        'checkbox_14',
        'chr_name',
        'city',
        'primary_contact',
        'client_name_primary',
        'client_name_only',
        'closing_date',
        'commission',
        'compliance_check_complete',
        'contact_name',
        'contract',
        'create_date',
        'created_by',
        'currency',
        'deadline_emails',
        'description',
        'double_ended',
        'exchange_rate',
        'expected_revenue',
        'financing',
        'first_contract',
        'full_address',
        'html_report',
        'import_batch_id',
        'lead_source',
        'lead_source_from',
        'lender_company',
        'lender_company_name',
        'lender_name',
        'loan_amount',
        'loan_type',
        'mls_no',
        'modern_mortgage_lender',
        'modified_by',
        'most_recent_note',
        'needs_new_date2',
        'needs_new_date',
        'needs_new_date1',
        'next_step',
        'ownership_type',
        'personal_transaction',
        'pipeline_probability',
        'potential_gci',
        'primary_contact_email',
        'primary_contact1',
        'probability',
        'pipeline1',
        'probable_volume',
        'property_type',
        'reason_for_loss',
        'representing',
        'review_gen_opt_out',
        'sale_price',
        'seller_concession_amount',
        'stage',
        'state',
        'status_reports',
        't',
        'tag',
        'contract_time_of_day_deadline',
        'tm_audit_complete',
        'tm_name',
        'tm_preference',
        'transaction_code',
        'transaction_image',
        'deal_name',
        'owner',
        'transaction_type',
        'type',
        'under_contract',
        'using_tm',
        'z_project_id',
        'zip',
    ];

    public function primaryContact()
    {
        return $this->belongsTo(Contact::class, 'primary_contact');
    }

    public function contactName()
    {
        return $this->belongsTo(Contact::class, 'contact_name');
    }

    /*  public function contract()
     {
         return $this->belongsTo(Contract::class);
     } */

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner');
    }

    public function tmName()
    {
        return $this->belongsTo(User::class, 'tm_name');
    }
}
