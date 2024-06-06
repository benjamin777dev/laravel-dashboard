<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'zoho_contact_id',
        'contact_owner',
        'email',
        'first_name',
        'last_name',
        'phone',
        'business_name',
        'business_information',
        'secondory_email',
        'relationship_type',
        'market_area',
        'envelope_salutation',
        'mobile',
        'created_time',
        'abcd',
        'mailing_address',
        'mailing_city',
        'mailing_state',
        'mailing_zip',
        'isContactCompleted',
        'isInZoho',
        'Lead_Source',
        'group_id',
        'referred_id',
        'lead_source_detail',
        'spouse_partner',
        'last_called',
        'last_emailed',
        'created_at',
        'updated_at',
        'email_blast_opt_in',
        'twitter_url',
        'emergency_contact_phone',
        'print_qr_code_sheet',
        'invalid_address_usps',
        'mls_recolorado',
        'mls_navica',
        'perfect',
        'realtor_board',
        'initial_split',
        'has_missing_important_date',
        'need_o_e',
        'culture_index',
        'sticky_dots',
        'strategy_group',
        'weekly_email',
        'number_of_chats',
        'notepad_mailer_opt_in',
        'chr_gives_amount',
        'other_zip',
        'market_mailer_opt_in',
        'groups',
        'closer_name_phone',
        'unsubscribe_from_reviews',
        'outsourced_mktg_onsite_video',
        'random_notes',
        'residual_cap',
        'email_blast_to_reverse_prospect_list',
        'review_generation',
        'zillow_url',
        'agent_assistant',
        'social_media_ads',
        'referred_by',
        'peer_advisor',
        'agent_name_on_marketing',
        'other_street',
        'qr_code_sign_rider',
        'google_business_page_url',
        'has_email',
        'salesforce_id',
        'mls_ires',
        'outsourced_mktg_floorplans',
        'income_goal',
        'chr_relationship',
        'locked_s',
        'tag',
        'import_batch',
        'termination_date',
        'license_start_date',
        'brokermint_id',
        'residual_split',
        'visitor_score',
        'sign_vendor',
        'other_state',
        'last_activity_time',
        'unsubscribed_mode',
        'license_number',
        'exchange_rate',
        'email_to_cc_on_all_marketing_comms',
        'tm_preference',
        'salutation_s',
        '$locked_for_me',
        '$approved',
        'email_cc_1',
        'google_business',
        'email_cc_2',
        'days_visited',
        'pipeline_stage',
        'social_media_images',
        'fees_charged_to_seller_at_closing',
        'realtor_com_url',
        'title_company',
        'select_your_prints',
        'role',
        'missing',
        'groups_tags',
        'lender_company_name',
        '$zia_owner_assignment',
        'secondary_email',
        'current_annual_academy',
        'transaction_status_reports',
        'non_tm_assignment',
        'user',
        'lender_email',
        'sign_install',
        'team_name',
        'pintrest_url',
        'youtube_url',
        'include_insights_in_intro',
        'import_id',
        'business_info',
        'email_signature',
        'property_website_qr_code',
        'draft_showing_instructions',
        'additional_email_for_confirmation',
        'important_date_added',
        'emergency_contact_name',
        'initial_cap',
        'unsubscribed_time',
        'mls_ppar',
        'outsourced_mktg_3d_zillow_tour',
        'marketing_specialist',               
        'default_commission_plan_id',        
        'agent_name_on_marketing',           
        'feature_cards_or_sheets',            
        'termination_reason',                
        'transaction_manager',                
        'auto_address',                      
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
    
    public function groups()
    {
        return $this->hasMany(ContactGroups::class, 'contactId');
    }

    public function dealContact()
    {
        return $this->hasMany(DealContact::class, 'contactId');
    }

    public function parentContact()
    {
        return $this->belongsTo(Contact::class, 'referred_id');
    }

    // Define the relationship for child categories
    public function childContact()
    {
        return $this->hasMany(Contact::class, 'referred_id');
    }
}
