<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\Helper;

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

    /**
     * Map Zoho data to contact model attributes.
     *
     * @param array $contact
     * @param User|null $user
     * @return array
     */
    public static function mapZohoData(array $contact, User $user = null)
    {
        $helper = new Helper();

        return [
            'contact_owner' => $user ? $user->root_user_id : null,
            'zoho_contact_id' => $contact['id'] ?? null,
            'email' => $contact['Email'] ?? null,
            'first_name' => $contact['First_Name'] ?? null,
            'last_name' => $contact['Last_Name'] ?? null,
            'phone' => $contact['Phone'] ?? null,
            'business_name' => $contact['Business_Name'] ?? null,
            'business_information' => $contact['Business_Info'] ?? null,
            'secondory_email' => $contact['Secondary_Email'] ?? null,
            'relationship_type' => $contact['Relationship_Type'] ?? null,
            'market_area' => $contact['Market_Area'] ?? null,
            'envelope_salutation' => $contact['Salutation'] ?? null,
            'mobile' => $contact['Mobile'] ?? null,
            'created_time' => isset($contact['Created_Time']) ? $helper->convertToUTC($contact['Created_Time']) : null,
            'abcd' => $contact['ABCD'] ?? null,
            'mailing_address' => $contact['Mailing_Street'] ?? null,
            'mailing_city' => $contact['Mailing_City'] ?? null,
            'mailing_state' => $contact['Mailing_State'] ?? null,
            'mailing_zip' => $contact['Mailing_Zip'] ?? null,
            'isContactCompleted' => isset($contact['Is_Active']) ? (bool)$contact['Is_Active'] : 1,
            'isInZoho' => isset($contact['$state']) && $contact['$state'] == 'save' ? 1 : 0,
            'Lead_Source' => $contact['Lead_Source'] ?? null,
            'referred_id' => $contact['Referred_By']['id'] ?? null,
            'lead_source_detail' => $contact['Lead_Source_Detail'] ?? null,
            'spouse_partner' => $contact['Spouse_Partner']['id'] ?? null,
            'last_called' => isset($contact['Last_Called']) ? $helper->convertToUTC($contact['Last_Called']) : null,
            'last_emailed' => isset($contact['Last_Emailed']) ? $helper->convertToUTC($contact['Last_Emailed']) : null,
            'email_blast_opt_in' => $contact['Email_Blast_Opt_In'] ?? null,
            'twitter_url' => $contact['Twitter_URL'] ?? null,
            'emergency_contact_phone' => $contact['Emergency_Contact_Phone'] ?? null,
            'print_qr_code_sheet' => $contact['Print_QR_Code_Sheet'] ?? null,
            'invalid_address_usps' => $contact['Invalid_Address_USPS'] ?? null,
            'mls_recolorado' => $contact['MLS_REColorado'] ?? null,
            'mls_navica' => $contact['MLS_Navica'] ?? null,
            'perfect' => $contact['Perfect'] ?? null,
            'realtor_board' => $contact['Realtor_Board'] ?? null,
            'initial_split' => $contact['Initial_Split'] ?? null,
            'has_missing_important_date' => $contact['HasMissingImportantDate'] ?? null,
            'need_o_e' => $contact['Need_O_E'] ?? null,
            'culture_index' => $contact['Culture_Index'] ?? null,
            'sticky_dots' => $contact['Sticky_Dots'] ?? null,
            'strategy_group' => $contact['Strategy_Group'] ?? null,
            'weekly_email' => $contact['Weekly_Email'] ?? null,
            'number_of_chats' => $contact['Number_Of_Chats'] ?? null,
            'notepad_mailer_opt_in' => $contact['Notepad_Mailer_Opt_In'] ?? null,
            'chr_gives_amount' => $contact['CHR_Gives_Amount'] ?? null,
            'other_zip' => $contact['Other_Zip'] ?? null,
            'market_mailer_opt_in' => $contact['Market_Mailer_Opt_In'] ?? null,
            'groups' => $contact['Groups'] ?? null,
            'closer_name_phone' => $contact['Closer_Name_Phone'] ?? null,
            'unsubscribe_from_reviews' => $contact['Unsubscribe_From_Reviews'] ?? null,
            'outsourced_mktg_onsite_video' => $contact['Outsourced_Mktg_Onsite_Video'] ?? null,
            'random_notes' => $contact['Random_Notes'] ?? null,
            'residual_cap' => $contact['Residual_Cap'] ?? null,
            'email_blast_to_reverse_prospect_list' => $contact['Email_Blast_to_Reverse_Prospect_List'] ?? null,
            'review_generation' => $contact['Review_Generation'] ?? null,
            'zillow_url' => $contact['Zillow_URL'] ?? null,
            'agent_assistant' => $contact['Agent_Assistant'] ?? null,
            'social_media_ads' => $contact['Social_Media_Ads'] ?? null,
            'referred_by' => $contact['Referred_By'] ?? null,
            'peer_advisor' => $contact['Peer_Advisor'] ?? null,
            'agent_name_on_marketing' => $contact['Agent_Name_on_Marketing'] ?? null,
            'other_street' => $contact['Other_Street'] ?? null,
            'qr_code_sign_rider' => $contact['QR_Code_Sign_Rider'] ?? null,
            'google_business_page_url' => $contact['Google_Business_Page_URL'] ?? null,
            'has_email' => $contact['Has_Email'] ?? null,
            'salesforce_id' => $contact['Salesforce_ID'] ?? null,
            'mls_ires' => $contact['MLS_IRES'] ?? null,
            'outsourced_mktg_floorplans' => $contact['Outsourced_Mktg_Floorplans'] ?? null,
            'income_goal' => $contact['Income_Goal'] ?? null,
            'chr_relationship' => $contact['CHR_Relationship'] ?? null,
            'locked_s' => $contact['Locked__s'] ?? null,
            'tag' => isset($contact['Tag']) ? json_encode($contact['Tag']) : null,
            'import_batch' => $contact['Import_Batch'] ?? null,
            'termination_date' => isset($contact['Termination_Date']) ? $helper->convertToUTC($contact['Termination_Date']) : null,
            'license_start_date' => isset($contact['License_Start_Date']) ? $helper->convertToUTC($contact['License_Start_Date']) : null,
            'brokermint_id' => $contact['Brokermint_ID'] ?? null,
            'residual_split' => $contact['Residual_Split'] ?? null,
            'visitor_score' => $contact['Visitor_Score'] ?? null,
            'sign_vendor' => $contact['Sign_Vendor'] ?? null,
            'other_state' => $contact['Other_State'] ?? null,
            'last_activity_time' => isset($contact['Last_Activity_Time']) ? $helper->convertToUTC($contact['Last_Activity_Time']) : null,
            'unsubscribed_mode' => $contact['Unsubscribed_Mode'] ?? null,
            'license_number' => $contact['License_Number'] ?? null,
            'exchange_rate' => $contact['Exchange_Rate'] ?? null,
            'email_to_cc_on_all_marketing_comms' => $contact['Email_to_CC_on_All_Marketing_Comms'] ?? null,
            'tm_preference' => $contact['TM_Preference'] ?? null,
            'salutation_s' => $contact['Salutation_s'] ?? null,
            '$locked_for_me' => $contact['$locked_for_me'] ?? null,
            '$approved' => $contact['$approved'] ?? null,
            'email_cc_1' => $contact['Email_CC_1'] ?? null,
            'google_business' => $contact['Google_Business'] ?? null,
            'email_cc_2' => $contact['Email_CC_2'] ?? null,
            'days_visited' => $contact['Days_Visited'] ?? null,
            'pipeline_stage' => $contact['Pipeline_Stage'] ?? null,
            'social_media_images' => $contact['Social_Media_Images'] ?? null,
            'fees_charged_to_seller_at_closing' => $contact['Fees_Charged_to_Seller_at_Closing'] ?? null,
            'realtor_com_url' => $contact['Realtor_com_URL'] ?? null,
            'title_company' => $contact['Title_Company'] ?? null,
            'select_your_prints' => $contact['Select_your_prints'] ?? null,
            'role' => $contact['Role'] ?? null,
            'missing' => $contact['Missing'] ?? null,
            'groups_tags' => $contact['Groups_Tags'] ?? null,
            'lender_company_name' => $contact['Lender_Company_Name'] ?? null,
            '$zia_owner_assignment' => $contact['$zia_owner_assignment'] ?? null,
            'secondary_email' => $contact['Secondary_Email'] ?? null,
            'current_annual_academy' => $contact['Current_Annual_Academy'] ?? null,
            'transaction_status_reports' => $contact['Transaction_Status_Reports'] ?? null,
            'non_tm_assignment' => $contact['Non_TM_Assignment'] ?? null,
            'user' => $contact['User']['id'] ?? null,
            'lender_email' => $contact['Lender_Email'] ?? null,
            'sign_install' => $contact['Sign_Install'] ?? null,
            'team_name' => $contact['Team_Name'] ?? null,
            'pintrest_url' => $contact['Pintrest_URL'] ?? null,
            'youtube_url' => $contact['Youtube_URL'] ?? null,
            'include_insights_in_intro' => $contact['Include_Insights_in_Intro'] ?? null,
            'import_id' => $contact['Import_ID'] ?? null,
            'business_info' => $contact['Business_Info'] ?? null,
            'email_signature' => $contact['Email_Signature'] ?? null,
            'property_website_qr_code' => $contact['Property_Website_QR_Code'] ?? null,
            'draft_showing_instructions' => $contact['Draft_Showing_Instructions'] ?? null,
            'additional_email_for_confirmation' => $contact['Additional_Email_for_Confirmation'] ?? null,
            'important_date_added' => $contact['Important_Date_Added'] ?? null,
            'emergency_contact_name' => $contact['Emergency_Contact_Name'] ?? null,
            'initial_cap' => $contact['Initial_Cap'] ?? null,
            'unsubscribed_time' => isset($contact['Unsubscribed_Time']) ? $helper->convertToUTC($contact['Unsubscribed_Time']) : null,
            'mls_ppar' => $contact['MLS_PPAR'] ?? null,
            'outsourced_mktg_3d_zillow_tour' => $contact['Outsourced_Mktg_3D_Zillow_Tour'] ?? null,
            'marketing_specialist' => $contact['Marketing_Specialist'] ?? null,
            'default_commission_plan_id' => $contact['Default_Commission_Plan_Id'] ?? null,
            'feature_cards_or_sheets' => $contact['Feature_Cards_or_Sheets'] ?? null,
            'termination_reason' => $contact['Termination_Reason'] ?? null,
            'transaction_manager' => $contact['Transaction_Manager'] ?? null,
            'auto_address' => $contact['Auto_Address'] ?? null,
        ];
    }
}
