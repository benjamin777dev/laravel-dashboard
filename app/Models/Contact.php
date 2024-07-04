<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
        'has_address'                 
    ];

    public static function getZohoContactInfo()
    {
        // Retrieve Zoho contact ID, last name, and first name
        return self::select('zoho_contact_id', 'last_name', 'first_name')->get();
    }

    public function userData()
    {
        return $this->belongsTo(User::class, 'contact_owner','root_user_id');
    }

    public function spouseContact()
    {
        return $this->belongsTo(Contact::class, 'spouse_partner', 'zoho_contact_id');
    }

    public function contactName()
    {
        return $this->belongsTo(Contact::class, 'contactId');
    }
    
    public function groupsData()
    {
        return $this->hasMany(ContactGroups::class, 'contactId');
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


    public function getSpouse()
    {
        if (is_null($this->spouse_partner) || $this->spouse_partner == '') {
            return null;
        }
    
        return Contact::where('zoho_contact_id', $this->spouse_partner)->first();
    }





    /**
     * Map Zoho data to contact model attributes.
     *
     * @param array $contact
     * @param User|null $user
     * @return array
     */
    public static function mapZohoData(array $data, $source)
    {
        $data['Created_Time'] = isset($data['Created_Time']) ? Carbon::parse($data['Created_Time'])->format('Y-m-d H:i:s') : null;
        $data['Last_Called'] = isset($data['Last_Called']) ? Carbon::parse($data['Last_Called'])->format('Y-m-d H:i:s')  : null;
        $data['Last_Emailed'] = isset($data['Last_Emailed']) ? Carbon::parse($data['Last_Emailed'])->format('Y-m-d H:i:s')  : null;
        $data['Modified_Time'] = isset($data['Modified_Time']) ? Carbon::parse($data['Modified_Time'])->format('Y-m-d H:i:s')  : null;
        $data['Termination_Date'] = isset($data['Termination_Date']) ? Carbon::parse($data['Termination_Date'])->format('Y-m-d H:i:s')  : null;
        $data['License_Start_Date'] = isset($data['License_Start_Date']) ? Carbon::parse($data['License_Start_Date'])->format('Y-m-d H:i:s') : null;
        $data['Unsubscribed_Time'] = isset($data['Unsubscribed_Time']) ? Carbon::parse($data['Unsubscribed_Time'])->format('Y-m-d H:i:s')  : null;
        $data['Last_Activity_Time'] = isset($data['Last_Activity_Time']) ? Carbon::parse($data['Last_Activity_Time'])->format('Y-m-d H:i:s')  : null;

        $incomeGoal = isset($data['Income_Goal']) ? $data['Income_Goal'] : null;
    
        // Remove commas and dollar signs for easier numeric validation
        if (!is_null($incomeGoal)) {
            $incomeGoal = str_replace(['$', ','], '', $incomeGoal);
        }


        $mappedData = [
            'zoho_contact_id' => $source === 'webhook' ? $data['id'] :  $data['Id'],
            'contact_owner' => $source === 'webhook' ? (isset($data['Owner']['id']) ? $data['Owner']['id'] : null) : (isset($data['Owner']) ? $data['Owner'] : null),
            'email' => isset($data['Email']) ? $data['Email'] : null,
            'first_name' => isset($data['First_Name']) ? $data['First_Name'] : null,
            'last_name' => isset($data['Last_Name']) ? $data['Last_Name'] : null,
            'phone' => isset($data['Phone']) ? $data['Phone'] : null,
            'business_name' => isset($data['Business_Name']) ? $data['Business_Name'] : null,
            'business_information' => isset($data['Business_Info']) ? $data['Business_Info'] : null,
            'secondary_email' => isset($data['Secondary_Email']) ? $data['Secondary_Email'] : null,
            'relationship_type' => isset($data['Relationship_Type']) ? $data['Relationship_Type'] : null,
            'market_area' => isset($data['Market_Area']) ? $data['Market_Area'] : null,
            'envelope_salutation' => isset($data['Salutation']) ? $data['Salutation'] : null,
            'mobile' => isset($data['Mobile']) ? $data['Mobile'] : null,
            'created_time' => $data['Created_Time'],
            'abcd' => isset($data['ABCD']) ? $data['ABCD'] : null,
            'mailing_address' => isset($data['Mailing_Street']) ? $data['Mailing_Street'] : null,
            'mailing_city' => isset($data['Mailing_City']) ? $data['Mailing_City'] : null,
            'mailing_state' => isset($data['Mailing_State']) ? $data['Mailing_State'] : null,
            'mailing_zip' => isset($data['Mailing_Zip']) ? $data['Mailing_Zip'] : null,
            'isContactCompleted' => $source == "webhook" || $source == "csv",
            'isInZoho' => $source == "webhook" || $source == "csv",
            'Lead_Source' => isset($data['Lead_Source']) ? $data['Lead_Source'] : null,
            'referred_id' => isset($data['Referred_By']) ? $data['Referred_By'] : (isset($data['Referred_By']["id"]) ? $data['Referred_By']["id"] : null),
            'lead_source_detail' => isset($data['Lead_Source_Detail']) ? $data['Lead_Source_Detail'] : null,
            'spouse_partner' => isset($data['Spouse_Partner']) ? (is_array($data['Spouse_Partner']) ? $data['Spouse_Partner']['id'] : $data['Spouse_Partner']) : null,
            'last_called' => $data['Last_Called'],
            'last_emailed' => $data['Last_Emailed'],
            'email_blast_opt_in' => isset($data[$source === 'webhook' ? 'Email_Blast_Opt_In' : 'Email_Opt_In']) ? (int)$data[$source === 'webhook' ? 'Email_Blast_Opt_In' : 'Email_Opt_In'] : null,
            'twitter_url' => isset($data[$source === 'webhook' ? 'Twitter_URL' : 'Twitter']) ? $data[$source === 'webhook' ? 'Twitter_URL' : 'Twitter'] : null,
            'emergency_contact_phone' => isset($data['Emergency_Contact_Phone']) ? $data['Emergency_Contact_Phone'] : null,
            'print_qr_code_sheet' => isset($data['Print_QR_Code_Sheet']) ? (int)$data['Print_QR_Code_Sheet'] : null,
            'invalid_address_usps' => isset($data['Invalid_Address_USPS']) ? (int)$data['Invalid_Address_USPS'] : null,
            'mls_recolorado' => isset($data['MLS_REColorado']) ? (int)$data['MLS_REColorado'] : null,
            'mls_navica' => isset($data['MLS_Navica']) ? (int)$data['MLS_Navica'] : null,
            'perfect' => isset($data['Perfect']) ? (int)$data['Perfect'] : null,
            'realtor_board' => isset($data['Realtor_Board']) ? $data['Realtor_Board'] : null,
            'initial_split' => isset($data['Initial_Split']) ? $data['Initial_Split'] : null,
            'has_missing_important_date' => isset($data['HasMissingImportantDate']) ? (int)$data['HasMissingImportantDate'] : null,
            'need_o_e' => isset($data['Need_O_E']) ? (int)$data['Need_O_E'] : null,
            'culture_index' => isset($data['Culture_Index']) ? $data['Culture_Index'] : null,
            'sticky_dots' => isset($data['Sticky_Dots']) ? $data['Sticky_Dots'] : null,
            'strategy_group' => isset($data['Strategy_Group']) ? $data['Strategy_Group'] : null,
            'weekly_email' => isset($data['Weekly_Email']) ? (int)$data['Weekly_Email'] : null,
            'number_of_chats' => isset($data['Number_Of_Chats']) ? $data['Number_Of_Chats'] : null,
            'notepad_mailer_opt_in' => isset($data['Notepad_Mailer_Opt_In']) ? (int)$data['Notepad_Mailer_Opt_In'] : null,
            'chr_gives_amount' => isset($data['CHR_Gives_Amount']) ? $data['CHR_Gives_Amount'] : null,
            'other_zip' => isset($data['Other_Zip']) ? $data['Other_Zip'] : null,
            'market_mailer_opt_in' => isset($data['Market_Mailer_Opt_In']) ? (int)$data['Market_Mailer_Opt_In'] : null,
            'groups' => isset($data['Groups']) ? $data['Groups'] : null,
            'closer_name_phone' => isset($data['Closer_Name_Phone']) ? $data['Closer_Name_Phone'] : null,
            'unsubscribe_from_reviews' => isset($data['Unsubscribe_From_Reviews']) ? (int)$data['Unsubscribe_From_Reviews'] : null,
            'outsourced_mktg_onsite_video' => isset($data['Outsourced_Mktg_Onsite_Video']) ? (int)$data['Outsourced_Mktg_Onsite_Video'] : null,
            'random_notes' => isset($data['Random_Notes']) ? $data['Random_Notes'] : null,
            'residual_cap' => isset($data['Residual_Cap']) ? $data['Residual_Cap'] : null,
            'email_blast_to_reverse_prospect_list' => isset($data['Email_Blast_to_Reverse_Prospect_List']) ? (int)$data['Email_Blast_to_Reverse_Prospect_List'] : null,
            'review_generation' => isset($data['Review_Generation']) ? (int)$data['Review_Generation'] : null,
            'zillow_url' => isset($data['Zillow_URL']) ? $data['Zillow_URL'] : null,
            'agent_assistant' => isset($data['Agent_Assistant']) ? $data['Agent_Assistant'] : null,
            'social_media_ads' => isset($data['Social_Media_Ads']) ? (int)$data['Social_Media_Ads'] : null,
            'referred_by' => isset($data['Referred_By']) ? json_encode($data['Referred_By']) : null,
            'peer_advisor' => isset($data['Peer_Advisor']) ? $data['Peer_Advisor'] : null,
            'agent_name_on_marketing' => isset($data['Agent_Name_on_Marketing']) ? $data['Agent_Name_on_Marketing'] : null,
            'other_street' => isset($data['Other_Street']) ? $data['Other_Street'] : null,
            'qr_code_sign_rider' => isset($data['QR_Code_Sign_Rider']) ? (int)$data['QR_Code_Sign_Rider'] : null,
            'google_business_page_url' => isset($data['Google_Business_Page_URL']) ? $data['Google_Business_Page_URL'] : null,
            'has_email' => isset($data['Has_Email']) ? (int)$data['Has_Email'] : null,
            'salesforce_id' => isset($data['Salesforce_ID']) ? $data['Salesforce_ID'] : null,
            'mls_ires' => isset($data['MLS_IRES']) ? (int)$data['MLS_IRES'] : null,
            'outsourced_mktg_floorplans' => isset($data['Outsourced_Mktg_Floorplans']) ? (int)$data['Outsourced_Mktg_Floorplans'] : null,
            'income_goal' => is_numeric($incomeGoal) ? $incomeGoal : 250000,
            'locked_s' => isset($data['Locked__s']) ? (int)$data['Locked__s'] : null,
            'tag' => isset($data['Tag']) ? json_encode($data['Tag']) : null,
            'import_batch' => isset($data['Import_Batch']) ? $data['Import_Batch'] : null,
            'termination_date' => $data['Termination_Date'],
            'license_start_date' => $data['License_Start_Date'],
            'brokermint_id' => isset($data['Brokermint_ID']) ? $data['Brokermint_ID'] : null,
            'residual_split' => isset($data['Residual_Split']) ? $data['Residual_Split'] : null,
            'visitor_score' => isset($data['Visitor_Score']) ? $data['Visitor_Score'] : null,
            'sign_vendor' => isset($data['Sign_Vendor']) ? $data['Sign_Vendor'] : null,
            'other_state' => isset($data['Other_State']) ? $data['Other_State'] : null,
            'last_activity_time' => $data['Last_Activity_Time'],
            'unsubscribed_mode' => isset($data['Unsubscribed_Mode']) ? $data['Unsubscribed_Mode'] : null,
            'license_number' => isset($data['License_Number']) ? $data['License_Number'] : null,
            'exchange_rate' => isset($data['Exchange_Rate']) ? $data['Exchange_Rate'] : null,
            'email_to_cc_on_all_marketing_comms' => isset($data['Email_to_CC_on_All_Marketing_Comms']) ? $data['Email_to_CC_on_All_Marketing_Comms'] : null,
            'tm_preference' => isset($data['TM_Preference']) ? $data['TM_Preference'] : null,
            'salutation_s' => isset($data['Salutation_s']) ? $data['Salutation_s'] : null,
            '$locked_for_me' => isset($data['$locked_for_me']) ? (int)$data['$locked_for_me'] : null,
            '$approved' => isset($data['$approved']) ? (int)$data['$approved'] : null,
            'email_cc_1' => isset($data['Email_CC_1']) ? $data['Email_CC_1'] : null,
            'google_business' => isset($data['Google_Business']) ? $data['Google_Business'] : null,
            'email_cc_2' => isset($data['Email_CC_2']) ? $data['Email_CC_2'] : null,
            'days_visited' => isset($data['Days_Visited']) ? $data['Days_Visited'] : null,
            'pipeline_stage' => isset($data['Pipeline_Stage']) ? $data['Pipeline_Stage'] : null,
            'social_media_images' => isset($data['Social_Media_Images']) ? (int)$data['Social_Media_Images'] : null,
            'fees_charged_to_seller_at_closing' => isset($data['Fees_Charged_to_Seller_at_Closing']) ? $data['Fees_Charged_to_Seller_at_Closing'] : null,
            'realtor_com_url' => isset($data['Realtor_com_URL']) ? $data['Realtor_com_URL'] : null,
            'title_company' => isset($data['Title_Company']) ? $data['Title_Company'] : null,
            'select_your_prints' => isset($data['Select_your_prints']) ? $data['Select_your_prints'] : null,
            'role' => isset($data['Role']) ? $data['Role'] : null,
            'missing' => isset($data['Missing']) ? $data['Missing'] : null,
            'groups_tags' => isset($data['Groups_Tags']) ? $data['Groups_Tags'] : null,
            'lender_company_name' => isset($data['Lender_Company_Name']) ? $data['Lender_Company_Name'] : null,
            '$zia_owner_assignment' => isset($data['$zia_owner_assignment']) ? $data['$zia_owner_assignment'] : null,
            'secondary_email' => isset($data['Secondary_Email']) ? $data['Secondary_Email'] : null,
            'current_annual_academy' => isset($data['Current_Annual_Academy']) ? (int)$data['Current_Annual_Academy'] : null,
            'transaction_status_reports' => isset($data['Transaction_Status_Reports']) ? (int)$data['Transaction_Status_Reports'] : null,
            'non_tm_assignment' => isset($data['Non_TM_Assignment']) ? $data['Non_TM_Assignment'] : null,
            'user' => $source === 'webhook' ? (isset($data['User']['id']) ? $data['User']['id'] : null) : (isset($data['User']) ? $data['User'] : null),
            'lender_email' => isset($data['Lender_Email']) ? $data['Lender_Email'] : null,
            'sign_install' => isset($data['Sign_Install']) ? (int)$data['Sign_Install'] : null,
            'team_name' => isset($data['Team_Name']) ? $data['Team_Name'] : null,
            'pintrest_url' => isset($data['Pintrest_URL']) ? $data['Pintrest_URL'] : null,
            'youtube_url' => isset($data['Youtube_URL']) ? $data['Youtube_URL'] : null,
            'include_insights_in_intro' => isset($data['Include_Insights_in_Intro']) ? (int)$data['Include_Insights_in_Intro'] : null,
            'import_id' => isset($data['Import_ID']) ? $data['Import_ID'] : null,
            'business_info' => isset($data['Business_Info']) ? $data['Business_Info'] : null,
            'email_signature' => isset($data['Email_Signature']) ? $data['Email_Signature'] : null,
            'property_website_qr_code' => isset($data['Property_Website_QR_Code']) ? (int)$data['Property_Website_QR_Code'] : null,
            'draft_showing_instructions' => isset($data['Draft_Showing_Instructions']) ? (int)$data['Draft_Showing_Instructions'] : null,
            'additional_email_for_confirmation' => isset($data['Additional_Email_for_Confirmation']) ? $data['Additional_Email_for_Confirmation'] : null,
            'important_date_added' => isset($data['Important_Date_Added']) ? (int)$data['Important_Date_Added'] : null,
            'emergency_contact_name' => isset($data['Emergency_Contact_Name']) ? $data['Emergency_Contact_Name'] : null,
            'initial_cap' => isset($data['Initial_Cap']) ? $data['Initial_Cap'] : null,
            'unsubscribed_time' => $data['Unsubscribed_Time'],
            'mls_ppar' => isset($data['MLS_PPAR']) ? (int)$data['MLS_PPAR'] : null,
            'outsourced_mktg_3d_zillow_tour' => isset($data['Outsourced_Mktg_3D_Zillow_Tour']) ? (int)$data['Outsourced_Mktg_3D_Zillow_Tour'] : null,
            'marketing_specialist' => isset($data['Marketing_Specialist']) ? $data['Marketing_Specialist'] : null,
            'default_commission_plan_id' => isset($data['Default_Commission_Plan_Id']) ? $data['Default_Commission_Plan_Id'] : null,
            'feature_cards_or_sheets' => isset($data['Feature_Cards_or_Sheets']) ? $data['Feature_Cards_or_Sheets'] : null,
            'termination_reason' => isset($data['Termination_Reason']) ? $data['Termination_Reason'] : null,
            'transaction_manager' => isset($data['Transaction_Manager']) ? $data['Transaction_Manager'] : null,
            'auto_address' => isset($data['Auto_Address']) ? $data['Auto_Address'] : null,
            'has_address' => !empty($data['Mailing_Street']) && !empty($data['Mailing_City']) && !empty($data['Mailing_State']) && !empty($data['Mailing_Zip']),
        ];
        
        if (isset($mappedData['email']) && isset($mappedData['chr_relationship']) && $mappedData['chr_relationship'] == 'Agent') {
            $user = User::where('email', $mappedData['email'])->first();
            if ($user) {
                $user->zoho_id = $mappedData['zoho_contact_id'];
                $user->goal = $mappedData['income_goal'] ?? 250000;
                $user->save();
            }
        }
        
        //Log::info("Mapped Data: " , ['data' => $mappedData]);

        return $mappedData;
    }
}
