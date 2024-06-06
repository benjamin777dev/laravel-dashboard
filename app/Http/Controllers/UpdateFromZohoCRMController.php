<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Contact;
use Carbon\Carbon;

class UpdateFromZohoCRMController extends Controller
{
    public function handleContactUpdate(Request $request)
    {
        $data = $request->all();

        Log::info('Received contact update from Zoho CRM', ['data' => $data]);

        // Extract necessary data from the payload
        $zohoContactId = $data['id'];

        // Ensure zoho_contact_id is included in the data array
        $data['zoho_contact_id'] = $zohoContactId;

        // Convert datetime fields to the correct format
        $data['Created_Time'] = $this->convertDateTimeFormat($data['Created_Time'] ?? null);
        $data['Last_Called'] = $this->convertDateTimeFormat($data['Last_Called'] ?? null);
        $data['Last_Emailed'] = $this->convertDateTimeFormat($data['Last_Emailed'] ?? null);
        $data['Modified_Time'] = $this->convertDateTimeFormat($data['Modified_Time'] ?? null);
        $data['Termination_Date'] = $this->convertDateTimeFormat($data['Termination_Date'] ?? null);
        $data['License_Start_Date'] = $this->convertDateTimeFormat($data['License_Start_Date'] ?? null);
        $data['Unsubscribed_Time'] = $this->convertDateTimeFormat($data['Unsubscribed_Time'] ?? null);
        $data['Last_Activity_Time'] = $this->convertDateTimeFormat($data['Last_Activity_Time'] ?? null);

        $data['tag'] = isset($data['Tag']) ? json_encode($data['Tag']) : null;

        $mappedData = [
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
            'transaction_manager' => $data['Transaction_Manager'] ?? null,
            'auto_address' => $data['Auto_Address'] ?? null,
        ];

        log::info("Mapped Data", ['mappedData' => $mappedData]);
        
        // Update or create the contact record in the database
        try {
            Contact::updateOrCreate(
                ['zoho_contact_id' => $zohoContactId],
                $mappedData
            );
        } catch (\Exception $e) {
            Log::error('Error updating contact', ['zoho_contact_id' => $zohoContactId, 'exception' => $e->getMessage()]);
            return response()->json(['error' => 'Error updating contact'], 500);
        }

        Log::info('Contact updated/inserted successfully', ['zoho_contact_id' => $zohoContactId]);

        return response()->json(['message' => 'Contact updated successfully'], 200);
    }

    private function convertDateTimeFormat($datetime)
    {
        if ($datetime) {
            try {
                return Carbon::parse($datetime)->format('Y-m-d H:i:s');
            } catch (\Exception $e) {
                Log::error('Error parsing datetime', ['datetime' => $datetime, 'exception' => $e->getMessage()]);
            }
        }
        return null;
    }
}
