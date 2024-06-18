<?php

namespace App\Models;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Ramsey\Uuid\Type\Decimal;

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
        'original_listing_price', 'tag', 'approval_state', 'status_reports',
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
    public static function mapZohoData(array $data, $source = "webhook")
    {

        $userId = null;

        //Log::info("Data: ". json_encode($data));
        // contact Name is a REQUIRED FIELD
        // no deals inthe database can exist without it
        // as zoho won't save a deal w/o one
        // so we don't need to check for null here
        $contactNameId = $source == "webhook" ? $data['Contact_Name']['id'] : $data['Contact_Name'];
        if ($contactNameId == null) {
            Log::error("No contact ID found! ", ['data'=> $data]);
            $contactNameId = $source == "webhook" ? $data['Owner']['id'] : $data['Owner'];
            if (!$contactNameId) {
                return new Deal();
            }
        }
        // now that we have a contact name id, which is the name of the agent
        // and id of the agent who is assigned this deal
        // we can find that user in the system
        $dealUser = User::where("zoho_id", $contactNameId)->first();
        if ($dealUser) {
            $userId = $dealUser->id;
        }


        $data['Created_Time'] = isset($data['Created_Time']) ? Carbon::parse($data['Created_Time'])->format('Y-m-d H:i:s') : null;
        $data['Modified_Time'] = isset($data['Modified_Time']) ? Carbon::parse($data['Modified_Time'])->format('Y-m-d H:i:s') : null;
        $data['Closing_Date'] = isset($data['Closing_Date']) ? Carbon::parse($data['Closing_Date'])->format('Y-m-d H:i:s') : null;
        $data['Create_Date'] = isset($data['Create_Date']) ? Carbon::parse($data['Create_Date'])->format('Y-m-d H:i:s') : null;
        $data['Lead_Conversion_Time'] = isset($data['Lead_Conversion_Time']) ? Carbon::parse($data['Lead_Conversion_Time'])->format('Y-m-d H:i:s') : null;
        
        $mappedData = [
            'address' => $data['Address'] ?? null,
            'approval_state' => $data['Approval_State'] ?? null,
            'brokerment_id' => $data['Brokermint_ID'] ?? null,
            'cda_notes' => $data['CDA_Notes'] ?? null,
            'check_received' => (int) ($data['Check_Received'] ?? null),
            'chr_name' => $data['CHR_Name'] ?? null,
            'city' => $data['City'] ?? null,
            'client_name_only' => $data['Client_Name_Only'] ?? null,
            'client_name_primary' => $data['Client_Name_Primary'] ?? null,
            'closing_date' => $data['Closing_Date'] ?? null,
            'commission' => (float)($data['Commission'] ?? null),
            'commission_flat_fee' => $data['Commission_Flat_Fee'] ?? null,
            'commission_flat_free' => $data['Commission_Flat_Fee'] ?? null,
            'compliance_check_complete' => (int) ($data['Compliance_Check_Complete'] ?? null),
            'contractId' => $source == "webhook" ? ((int)($data['Contract']['id'] ?? null)) : ((int)($data['Contract'] ?? null)),
            'contactId' => $contactNameId,
            'contact_name' => $source == "webhook" ? ($data['Contact_Name']['id'] ?? null) : null,
            'contact_name_id' => $contactNameId,
            'contract_time_of_day_deadline' => $data['Contract_Time_of_Day_Deadline'] ?? null,
            'create_date' => $data['Create_Date'] ?? null,
            'created_by' => json_encode($data['Created_By']) ?? null,
            'created_by_email' => $data['Created_By']['email'] ?? null,
            'created_by_id' => $source == "webhook" ? ($data['Created_By']['id'] ?? null) : ($data["Created_By"] ?? null),
            'created_by_name' => $data['Created_By']['name'] ?? null,
            'currency' => $data['Currency'] ?? null,
            'deal_name' => $data['Deal_Name'] ?? null,
            'deadline_em_opt_out' => (int) ($data['Deadline_Emails'] ?? null),
            'deadline_emails' => (int) ($data['Deadline_Emails'] ?? null),
            'double_ended' => (int) ($data['Double_Ended'] ?? null),
            'exchange_rate' => (float)($data['Exchange_Rate'] ?? null),
            'final_commission_for_agent' => $data['Final_Commission_for_Agent'] ?? null,
            'final_commission_for_co_op_agent_flat_fee' => $data['Final_Commission_for_Co_Op_Agent_Flat_Fee'] ?? null,
            'financing' => $data['Financing'] ?? null,
            'full_address' => $data['Full_Address'] ?? null,
            'html_report' => $data['HTML_Report'] ?? null,
            'import_batch_id' => $data['Import_Batch_ID'] ?? null,
            'isDealCompleted' => (int) ($data['isDealCompleted'] ?? 1),
            'isInZoho' => (int) ($data['isInZoho'] ?? 1),
            'lead_agent' => $data['Lead_Agent']['name'] ?? null,
            'lead_agent_email' => $data['Lead_Agent']['email'] ?? null,
            'lead_agent_id' => $source == "webhook" ? ($data['Lead_Agent']['id'] ?? null) : ($data["Lead_Agent"] ?? null),
            'lead_agent_name' => $data['Lead_Agent']['name'] ?? null,
            'lead_conversion_time' => $data['Lead_Conversion_Time'] ?? null,
            'lender_company' => $data['Lender_Company'] ?? null,
            'lender_company_name' => $data['Lender_Company_Name'] ?? null,
            'lender_name' => $data['Lender_Name'] ?? null,
            'locked_s' => (int) ($data['Locked__s'] ?? null),
            'marketing_specialist' => $data['Marketing_Specialist'] ?? null,
            'modern_mortgage_lender' => $data['Modern_Mortgage_Lender'] ?? null,
            'modified_by_email' => $data['Modified_By']['email'] ?? null,
            'modified_by_id' => $source == "webhook" ? ($data['Modified_By']['id'] ?? null) : ($data["Modified_By"] ?? null),
            'modified_by_name' => $data['Modified_By']['name'] ?? null,
            'modified_time' => $data['Modified_Time'] ?? null,
            'most_recent_note' => $data['Most_Recent_Note'] ?? null,
            'needs_new_date2' => (int) ($data['Needs_New_Date2'] ?? null),
            'original_co_op_commission' => $data['Original_Co_Op_Commission'] ?? null,
            'original_co_op_commission_flat_fee' => $data['Original_Co_Op_Commission_Flat_Fee'] ?? null,
            'original_commission_for_agent_flat_fee' => $data['Original_Commission_For_Agent_Flat_Fee'] ?? null,
            'original_listing_price' => (float)($data['Original_Listing_Price'] ?? null),
            'overall_sales_duration' => (int)($data['Overall_Sales_Duration'] ?? null),
            'owner_email' => $data['Owner']['email'] ?? null,
            'owner_id' => $source == "webhook" ? ($data['Owner']['id'] ?? null) : ($data['Owner'] ?? null),
            'owner_name' => $data['Owner']['name'] ?? null,
            'ownership_type' => $data['Ownership_Type'] ?? null,
            'personal_transaction' => (int) ($data['Personal_Transaction'] ?? null),
            'pipeline1' => $data['Pipeline1'] ?? null,
            'pipeline_probability' => (float)($data['Pipeline_Probability'] ?? null),
            'potential_gci' => $data['Potential_GCI'] ?? null,
            'primary_contact_email' => $data['Primary_Contact_Email'] ?? null,
            'probability' => (float)($data['Probability'] ?? null),
            'probable_volume' => $data['Probable_Volume'] ?? null,
            'property_type' => $data['Property_Type'] ?? null,
            'representing' => $data['Representing'] ?? null,
            'review_gen_opt_out' => (int) ($data['Review_Gen_Opt_Out'] ?? null),
            'review_process' => json_encode($data['review_process'] ?? null),
            'sale_price' => (float)($data['Sale_Price'] ?? null),
            'sales_cycle_duration' => (int)($data['Sales_Cycle_Duration'] ?? null),
            'stage' => $data['Stage'] ?? null,
            'state' => $data['State'] ?? null,
            'status_reports' => (int) ($data['Status_Reports'] ?? null),
            'status_rpt_opt_out' => (int) ($data['status_rpt_opt_out'] ?? 0),
            'tag' => json_encode($data['Tag'] ?? null),
            'tm_audit_complete' => (int) ($data['TM_Audit_Complete'] ?? null),
            'tm_name' => $data['TM_Name']['name'] ?? null,
            'tm_name_id' => $source == "webhook" ? ($data['TM_Name']['id'] ?? null) : ($data['TM_Name'] ?? null),
            'tm_preference' => $data['TM_Preference'] ?? null,
            'transaction_code' => $data['Transaction_Code'] ?? null,
            'under_contract' => (int) ($data['Under_Contract'] ?? null),
            'userID' => $userId,
            'zoho_deal_createdTime' => $data['Created_Time'] ?? null,
            'zoho_deal_id' => $source == "webhook" ? $data['id'] : $data['Id'],
            'z_project_id' => $data['Z_Project_ID'] ?? null,
            'zip' => $data['Zip'] ?? null,
        ];
        
        return $mappedData;
    }

}
