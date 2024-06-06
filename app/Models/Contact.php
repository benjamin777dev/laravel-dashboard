<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\Helper;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

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
        $data = $contact;

        Log::info('Received contact update from Zoho CRM', ['data' => $data]);

        // Extract necessary data from the payload
        $zohoContactId = $data['id'];

        // Ensure zoho_contact_id is included in the data array
        $data['zoho_contact_id'] = $zohoContactId;

        // Convert datetime fields to the correct format
        $data['Created_Time'] = isset($data['Created_time']) ? Carbon::parse($data['Created_Time'])->format('Y-m-d H:i:s') : null;
        $data['Last_Called'] = isset($data['Last_Called']) ? Carbon::parse($data['Last_Called'])->format('Y-m-d H:i:s') : null;
        $data['Last_Emailed'] = isset($data['Last_Emailed']) ? Carbon::parse($data['Last_Emailed'])->format('Y-m-d H:i:s') : null;
        $data['Modified_Time'] = isset($data['Modified_Time']) ? Carbon::parse($data['Modified_Time'])->format('Y-m-d H:i:s') : null;
        $data['Termination_Date'] = isset($data['Termination_Date']) ? Carbon::parse($data['Termination_Date'])->format('Y-m-d H:i:s') : null;
        $data['License_Start_Date'] = isset($data['License_Start_Date']) ? Carbon::parse($data['License_Start_Date'])->format('Y-m-d H:i:s') : null;
        $data['Unsubscribed_Time'] = isset($data['Unsubscribed_Time']) ? Carbon::parse($data['Unsubscribed_Time'])->format('Y-m-d H:i:s') : null;
        $data['Last_Activity_Time'] = isset($data['Last_Activity_Time']) ? Carbon::parse($data['Last_Activity_Time'])->format('Y-m-d H:i:s') : null;

        $data['tag'] = isset($data['Tag']) ? json_encode($data['Tag']) : null;



        $mappedData =  [
            'contact_owner' => $data['Owner']['id'] ?? null,
            'zoho_contact_id' => $zohoContactId,
            'email' => $data['Email'] ?? null,
            'first_name' => $data['First_Name'] ?? null,
            'last_name' => $data['Last_Name'] ?? null,
            'phone' => $data['Phone'] ?? null,
            'created_time' => $data['Created_Time'] ?? null,
            'abcd' => $data['ABCD'] ?? null,
            'mailing_address' => $data['Mailing_Street'] ?? null,
            'mailing_city' => $data['Mailing_City'] ?? null,
            'relationship_type' => $data['Relationship_Type'] ?? null,
            'market_area' => $data['Market_Area'] ?? null,
            'envelope_salutation' => $data['Salutation_s'] ?? null,
            'mailing_state' => $data['Mailing_State'] ?? null,
            'mailing_zip' => $data['Mailing_Zip'] ?? null,
            'isContactCompleted' => $data['Is_Active'] ?? 1,
            'isInZoho' => $data['$state'] == 'save' ? 1 : 0,
            'mobile' => $data['Mobile'] ?? null,
            'business_name' => $data['Business_Name'] ?? null,
            'business_information' => $data['Business_Info'] ?? null,
            'secondory_email' => $data['Secondary_Email'] ?? null,
            'Lead_Source' => $data['Lead_Source'] ?? null,
            'lead_source_detail' => $data['Lead_Source_Detail'] ?? null,
            'spouse_partner' => json_encode($data['Spouse_Partner']) ?? null,
            'last_called' => $data['Last_Called'] ?? null,
            'last_emailed' => $data['Last_Emailed'] ?? null,
            'created_at' => now(),
            'updated_at' => now(),
            'email_blast_opt_in' => $data['Email_Blast_Opt_In'] ?? null,
            'twitter_url' => $data['Twitter_URL'] ?? null,
            'emergency_contact_phone' => $data['Emergency_Contact_Phone'] ?? null,
            'print_qr_code_sheet' => $data['Print_QR_Code_Sheet'] ?? null,
            'invalid_address_usps' => $data['Invalid_Address_USPS'] ?? null,
            'mls_recolorado' => $data['MLS_REColorado'] ?? null,
            'mls_navica' => $data['MLS_Navica'] ?? null,
            'perfect' => $data['Perfect'] ?? null,
            'realtor_board' => $data['Realtor_Board'] ?? null,
            'initial_split' => $data['Initial_Split'] ?? null,
            'has_missing_important_date' => $data['HasMissingImportantDate'] ?? null,
            'need_o_e' => $data['Need_O_E'] ?? null,
            'culture_index' => $data['Culture_Index'] ?? null,
            'sticky_dots' => $data['Sticky_Dots'] ?? null,
            'strategy_group' => $data['Strategy_Group'] ?? null,
            'weekly_email' => $data['Weekly_Email'] ?? null,
            'number_of_chats' => $data['Number_Of_Chats'] ?? null,
            'notepad_mailer_opt_in' => $data['Notepad_Mailer_Opt_In'] ?? null,
            'chr_gives_amount' => $data['CHR_Gives_Amount'] ?? null,
            'other_zip' => $data['Other_Zip'] ?? null,
            'market_mailer_opt_in' => $data['Market_Mailer_Opt_In'] ?? null,
            'groups' => $data['Groups'] ?? null,
            'closer_name_phone' => $data['Closer_Name_Phone'] ?? null,
            'unsubscribe_from_reviews' => $data['Unsubscribe_From_Reviews'] ?? null,
            'outsourced_mktg_onsite_video' => $data['Outsourced_Mktg_Onsite_Video'] ?? null,
            'random_notes' => $data['Random_Notes'] ?? null,
            'residual_cap' => $data['Residual_Cap'] ?? null,
            'email_blast_to_reverse_prospect_list' => $data['Email_Blast_to_Reverse_Prospect_List'] ?? null,
            'review_generation' => $data['Review_Generation'] ?? null,
            'zillow_url' => $data['Zillow_URL'] ?? null,
            'agent_assistant' => $data['Agent_Assistant'] ?? null,
            'social_media_ads' => $data['Social_Media_Ads'] ?? null,
            'referred_by' => $data['Referred_By'] ?? null,
            'peer_advisor' => $data['Peer_Advisor'] ?? null,
            'agent_name_on_marketing' => $data['Agent_Name_on_Marketing'] ?? null,
            'other_street' => $data['Other_Street'] ?? null,
            'qr_code_sign_rider' => $data['QR_Code_Sign_Rider'] ?? null,
            'google_business_page_url' => $data['Google_Business_Page_URL'] ?? null,
            'has_email' => $data['Has_Email'] ?? null,
            'salesforce_id' => $data['Salesforce_ID'] ?? null,
            'mls_ires' => $data['MLS_IRES'] ?? null,
            'outsourced_mktg_floorplans' => $data['Outsourced_Mktg_Floorplans'] ?? null,
            'income_goal' => $data['Income_Goal'] ?? null,
            'chr_relationship' => $data['CHR_Relationship'] ?? null,
            'locked_s' => $data['Locked__s'] ?? null,
            'tag' => json_encode($data['Tag']) ?? null,
            'import_batch' => $data['Import_Batch'] ?? null,
            'termination_date' => $data['Termination_Date'] ?? null,
            'license_start_date' => $data['License_Start_Date'] ?? null,
            'brokermint_id' => $data['Brokermint_ID'] ?? null,
            'residual_split' => $data['Residual_Split'] ?? null,
            'visitor_score' => $data['Visitor_Score'] ?? null,
            'sign_vendor' => $data['Sign_Vendor'] ?? null,
            'other_state' => $data['Other_State'] ?? null,
            'last_activity_time' => $data['Last_Activity_Time'] ?? null,
            'unsubscribed_mode' => $data['Unsubscribed_Mode'] ?? null,
            'license_number' => $data['License_Number'] ?? null,
            'exchange_rate' => $data['Exchange_Rate'] ?? null,
            'email_to_cc_on_all_marketing_comms' => $data['Email_to_CC_on_All_Marketing_Comms'] ?? null,
            'tm_preference' => $data['TM_Preference'] ?? null,
            'salutation_s' => $data['Salutation_s'] ?? null,
            '$locked_for_me' => $data['$locked_for_me'] ?? null,
            '$approved' => $data['$approved'] ?? null,
            'email_cc_1' => $data['Email_CC_1'] ?? null,
            'google_business' => $data['Google_Business'] ?? null,
            'email_cc_2' => $data['Email_CC_2'] ?? null,
            'days_visited' => $data['Days_Visited'] ?? null,
            'pipeline_stage' => $data['Pipeline_Stage'] ?? null,
            'social_media_images' => $data['Social_Media_Images'] ?? null,
            'fees_charged_to_seller_at_closing' => $data['Fees_Charged_to_Seller_at_Closing'] ?? null,
            'realtor_com_url' => $data['Realtor_com_URL'] ?? null,
            'title_company' => $data['Title_Company'] ?? null,
            'select_your_prints' => $data['Select_your_prints'] ?? null,
            'role' => $data['Role'] ?? null,
            'missing' => $data['Missing'] ?? null,
            'groups_tags' => $data['Groups_Tags'] ?? null,
            'lender_company_name' => $data['Lender_Company_Name'] ?? null,
            '$zia_owner_assignment' => $data['$zia_owner_assignment'] ?? null,
            'secondary_email' => $data['Secondary_Email'] ?? null,
            'current_annual_academy' => $data['Current_Annual_Academy'] ?? null,
            'transaction_status_reports' => $data['Transaction_Status_Reports'] ?? null,
            'non_tm_assignment' => $data['Non_TM_Assignment'] ?? null,
            'user' => $data['User']['id'] ?? null,
            'lender_email' => $data['Lender_Email'] ?? null,
            'sign_install' => $data['Sign_Install'] ?? null,
            'team_name' => $data['Team_Name'] ?? null,
            'pintrest_url' => $data['Pintrest_URL'] ?? null,
            'youtube_url' => $data['Youtube_URL'] ?? null,
            'include_insights_in_intro' => $data['Include_Insights_in_Intro'] ?? null,
            'import_id' => $data['Import_ID'] ?? null,
            'business_info' => $data['Business_Info'] ?? null,
            'email_signature' => $data['Email_Signature'] ?? null,
            'phone' => $data['Phone'] ?? null,
            'property_website_qr_code' => $data['Property_Website_QR_Code'] ?? null,
            'draft_showing_instructions' => $data['Draft_Showing_Instructions'] ?? null,
            'additional_email_for_confirmation' => $data['Additional_Email_for_Confirmation'] ?? null,
            'important_date_added' => $data['Important_Date_Added'] ?? null,
            'emergency_contact_name' => $data['Emergency_Contact_Name'] ?? null,
            'initial_cap' => $data['Initial_Cap'] ?? null,
            'unsubscribed_time' => $data['Unsubscribed_Time'] ?? null,
            'mls_ppar' => $data['MLS_PPAR'] ?? null,
            'outsourced_mktg_3d_zillow_tour' => $data['Outsourced_Mktg_3D_Zillow_Tour'] ?? null,
            'marketing_specialist' => $data['Marketing_Specialist'] ?? null,
            'default_commission_plan_id' => $data['Default_Commission_Plan_Id'] ?? null,
            'feature_cards_or_sheets' => $data['Feature_Cards_or_Sheets'] ?? null,
            'termination_reason' => $data['Termination_Reason'] ?? null,
            'transaction_manager' => json_encode($data['Transaction_Manager']) ?? null,
            'auto_address' => $data['Auto_Address'] ?? null,
        ];

        Log::info("Mapped Data: ". json_encode($mappedData));
        return $mappedData;
    }
}
