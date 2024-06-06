<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Carbon\Carbon;

class Deal extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'zip', 'personal_transaction', 'double_ended', 'userID', 'address', 
        'representing', 'client_name_only', 'commission', 'probable_volume', 
        'lender_company', 'closing_date', 'ownership_type', 'needs_new_date2', 
        'deal_name', 'tm_preference', 'tm_name', 'stage', 'sale_price', 
        'zoho_deal_id', 'pipeline1', 'pipeline_probability', 'zoho_deal_createdTime', 
        'property_type', 'city', 'state', 'lender_company_name', 'client_name_primary', 
        'lender_name', 'potential_gci', 'created_by', 'contractId', 'contactId', 
        'review_gen_opt_out', 'commission_flat_free', 'deadline_em_opt_out', 
        'status_rpt_opt_out', 'isDealCompleted', 'isInZoho', 'lead_agent', 
        'financing', 'modern_mortgage_lender', 'contact_name', 'brokerment_id', 
        'owner_name', 'owner_id', 'owner_email', 'final_commission_for_co_op_agent_flat_fee', 
        'compliance_check_complete', 'html_report', 'full_address', 'currency', 
        'tm_name_id', 'created_by_name', 'created_by_id', 'created_by_email', 
        'original_co_op_commission', 'original_co_op_commission_flat_fee', 
        'lead_agent_id', 'lead_agent_name', 'lead_agent_email', 'contact_name_id', 
        'most_recent_note', 'exchange_rate', 'deadline_emails', 'z_project_id', 
        'import_batch_id', 'original_commission_for_agent_flat_fee', 'marketing_specialist', 
        'review_process', 'final_commission_for_agent', 'modified_by_name', 
        'modified_by_id', 'modified_by_email', 'lead_conversion_time', 
        'overall_sales_duration', 'tm_audit_complete', 'primary_contact_email', 
        'chr_name', 'under_contract', 'modified_time', 'probability', 'transaction_code', 
        'cda_notes', 'contract_time_of_day_deadline', 'sales_cycle_duration', 
        'commission_flat_fee', 'check_received', 'locked_s', 'create_date', 
        'original_listing_price', 'tag', 'approval_state', 'status_reports'
    ];

    public function userData()
    {
        return $this->belongsTo(User::class, 'userID');
    }

    public function contactName()
    {
        return $this->belongsTo(Contact::class, 'contact_name');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class, 'zoho_deal_id', 'what_id');
    }

    public function tmName()
    {
        return $this->belongsTo(User::class, 'tm_name', 'root_user_id');
    }
    
    public function leadAgent()
    {
        return $this->belongsTo(User::class, 'lead_agent', 'root_user_id');
    }

    /**
     * Map Zoho data to deal model attributes.
     *
     * @param array $data
     * @return array
     */
    public static function mapZohoData(array $data, $source = 'webhook')
{
    $data['Created_Time'] = isset($data['Created_Time']) ? Carbon::parse($data['Created_Time'])->format('Y-m-d H:i:s') : null;
    $data['Last_Activity_Time'] = isset($data['Last_Activity_Time']) ? Carbon::parse($data['Last_Activity_Time'])->format('Y-m-d H:i:s')  : null;
    $data['Modified_Time'] = isset($data['Modified_Time']) ? Carbon::parse($data['Modified_Time'])->format('Y-m-d H:i:s')  : null;
    $data['Closing_Date'] = isset($data['Closing_Date']) ? Carbon::parse($data['Closing_Date'])->format('Y-m-d H:i:s') : null;

    $contactNameId = isset($data['Contact_Name']['id']) ? $data['Contact_Name']['id'] : null;
    if ($contactNameId) {
        $user = User::where('zoho_id', $contactNameId)->first();
        $userID = $user ? $user->id : null;
    } else {
        $userID = null;
    }

    $mappedData = [
        'zip' => $data['Zip'],
        'personal_transaction' => (int)$data['Personal_Transaction'],
        'brokerment_id' => $data['Brokermint_ID'],
        'owner_name' => isset($data['Owner']['name']) ? $data['Owner']['name'] : null,
        'owner_id' => isset($data['Owner']['id']) ? $data['Owner']['id'] : null,
        'owner_email' => isset($data['Owner']['email']) ? $data['Owner']['email'] : null,
        'address' => $data['Address'],
        'final_commission_for_co_op_agent_flat_fee' => $data['Final_Commission_for_Co_Op_Agent_Flat_Fee'],
        'client_name_only' => $data['Client_Name_Only'],
        'commission' => $data['Commission'],
        'probable_volume' => $data['Probable_Volume'],
        'lender_company' => $data['Lender_Company'],
        'compliance_check_complete' => (int)$data['Compliance_Check_Complete'],
        'html_report' => $data['HTML_Report'],
        'full_address' => $data['Full_Address'],
        'currency' => $data['Currency'],
        'tm_preference' => $data['TM_Preference'],
        'tm_name_id' => isset($data['TM_Name']['id']) ? $data['TM_Name']['id'] : null,
        'tm_name' => isset($data['TM_Name']['name']) ? $data['TM_Name']['name'] : null,
        'stage' => $data['Stage'],
        'sale_price' => $data['Sale_Price'],
        'zoho_deal_id' => $data['id'],
        'pipeline1' => $data['Pipeline1'],
        'pipeline_probability' => $data['Pipeline_Probability'],
        'zoho_deal_createdTime' => $data['Created_Time'],
        'property_type' => $data['Property_Type'],
        'city' => $data['City'],
        'state' => $data['State'],
        'lender_company_name' => $data['Lender_Company_Name'],
        'client_name_primary' => $data['Client_Name_Primary'],
        'lender_name' => $data['Lender_Name'],
        'potential_gci' => $data['Potential_GCI'],
        'created_by_name' => isset($data['Created_By']['name']) ? $data['Created_By']['name'] : null,
        'created_by_id' => isset($data['Created_By']['id']) ? $data['Created_By']['id'] : null,
        'created_by_email' => isset($data['Created_By']['email']) ? $data['Created_By']['email'] : null,
        'review_gen_opt_out' => (int)$data['Review_Gen_Opt_Out'],
        'original_co_op_commission' => $data['Original_Co_Op_Commission'],
        'original_co_op_commission_flat_fee' => $data['Original_Co_Op_Commission_Flat_Fee'],
        'lead_agent_id' => isset($data['Lead_Agent']['id']) ? $data['Lead_Agent']['id'] : null,
        'lead_agent_name' => isset($data['Lead_Agent']['name']) ? $data['Lead_Agent']['name'] : null,
        'lead_agent_email' => isset($data['Lead_Agent']['email']) ? $data['Lead_Agent']['email'] : null,
        'financing' => $data['Financing'],
        'modern_mortgage_lender' => $data['Modern_Mortgage_Lender'],
        'contact_name_id' => isset($data['Contact_Name']['id']) ? $data['Contact_Name']['id'] : null,
        'contact_name' => isset($data['Contact_Name']['name']) ? $data['Contact_Name']['name'] : null,
        'contractId' => isset($data['Contract']['id']) ? $data['Contract']['id'] : null,
        'created_by' => json_encode($data['Created_By']),
        'zoho_deal_createdTime' => $data['Created_Time'],
        'zoho_deal_id' => $data['id'],
        'closing_date' => $data['Closing_Date'],
        'deal_name' => $data['Deal_Name'],
        'pipeline1' => $data['Pipeline1'],
        'pipeline_probability' => $data['Pipeline_Probability'],
        'property_type' => $data['Property_Type'],
        'city' => $data['City'],
        'state' => $data['State'],
        'lender_company_name' => $data['Lender_Company_Name'],
        'client_name_primary' => $data['Client_Name_Primary'],
        'lender_name' => $data['Lender_Name'],
        'potential_gci' => $data['Potential_GCI'],
        'contactId' => isset($data['Client_Name_Only']) ? explode('||', $data['Client_Name_Only'])[1] : null,
        'most_recent_note' => $data['Most_Recent_Note'],
        'exchange_rate' => $data['Exchange_Rate'],
        'deadline_emails' => (int)$data['Deadline_Emails'],
        'z_project_id' => $data['Z_Project_ID'],
        'import_batch_id' => $data['Import_Batch_ID'],
        'original_commission_for_agent_flat_fee' => $data['Original_Commission_For_Agent_Flat_Fee'],
        'marketing_specialist' => $data['Marketing_Specialist'],
        'review_process' => isset($data['$review_process']) ? json_encode($data['$review_process']) : null,
        'final_commission_for_agent' => $data['Final_Commission_for_Agent'],
        'modified_by_name' => isset($data['Modified_By']['name']) ? $data['Modified_By']['name'] : null,
        'modified_by_id' => isset($data['Modified_By']['id']) ? $data['Modified_By']['id'] : null,
        'modified_by_email' => isset($data['Modified_By']['email']) ? $data['Modified_By']['email'] : null,
        'lead_conversion_time' => $data['Lead_Conversion_Time'],
        'overall_sales_duration' => $data['Overall_Sales_Duration'],
        'tm_audit_complete' => (int)$data['TM_Audit_Complete'],
        'primary_contact_email' => $data['Primary_Contact_Email'],
        'chr_name' => $data['CHR_Name'],
        'under_contract' => (int)$data['Under_Contract'],
        'modified_time' => $data['Modified_Time'],
        'probability' => $data['Probability'],
        'transaction_code' => $data['Transaction_Code'],
        'cda_notes' => $data['CDA_Notes'],
        'contract_time_of_day_deadline' => $data['Contract_Time_of_Day_Deadline'],
        'sales_cycle_duration' => $data['Sales_Cycle_Duration'],
        'commission_flat_fee' => $data['Commission_Flat_Fee'],
        'check_received' => (int)$data['Check_Received'],
        'locked_s' => (int)$data['Locked__s'],
        'create_date' => $data['Create_Date'],
        'original_listing_price' => $data['Original_Listing_Price'],
        'tag' => isset($data['Tag']) ? json_encode($data['Tag']) : null,
        'approval_state' => $data['$approval_state'],
        'status_reports' => (int)$data['Status_Reports'],
        'double_ended' => (int)$data['Double_Ended'],
        'userID' => $userID,
        'representing' => $data['Representing'],
        'ownership_type' => $data['Ownership_Type'],
        'needs_new_date2' => (int)$data['Needs_New_Date2'],
        'lead_agent' => isset($data['Lead_Agent']['name']) ? $data['Lead_Agent']['name'] : null,
        'isDealCompleted' => isset($data['isDealCompleted']) ? (int)$data['isDealCompleted'] : 1,
        'isInZoho' => isset($data['isInZoho']) ? (int)$data['isInZoho'] : 1,
        'commission_flat_free' => $data['Commission_Flat_Fee'],
        'deadline_em_opt_out' => (int)$data['Deadline_Emails'],
        'status_rpt_opt_out' => isset($data['status_rpt_opt_out']) ? (int)$data['status_rpt_opt_out'] : 0,
    ];

    return $mappedData;
}

}
