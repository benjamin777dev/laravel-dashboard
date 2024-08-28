<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class Aci extends Model
{
    use HasFactory;

    protected $table = 'agent_commission_incomes';

    protected $fillable = [
        'adjusted_gross_commission',
        'admin_fee_income',
        'zoho_aci_id',
        'after_splits',
        'agent_check_amount',
        'record_image',
        'owner_id',
        'agent_contribution_to_client_transaction_costs',
        'name',
        'agent_portion_of_commission_that_gets_split',
        'agent_team_for_capping',
        'chr_agent_id',
        'calculated_count',
        'calculated_gci',
        'calculated_volume',
        'chr_gives',
        'chr_gives_due_to_chr',
        'closing_date',
        'colorado_home_realty',
        'commission_notes',
        'commission_percent',
        'credit_to_client',
        'currency',
        'current_year',
        'double_ended',
        'ecommission',
        'ecommission_payout',
        'email',
        'email_opt_out',
        'exchange_rate',
        'gt',
        'home_warranty',
        'home_warranty_payout',
        'import_batch_id',
        'irs_reported_1099_income_for_this_transaction',
        'less_initial_split_to_chr',
        'less_residual_split_to_chr',
        'less_split_to_chr',
        'mentee_amount_paid',
        'np1',
        'past_due_amount_to_chr',
        'personal_transaction',
        'portion_of_total',
        'representing',
        'sale_price',
        'secondary_email',
        'sides',
        'split_percent',
        'split_to_chr',
        'stage',
        'strategy_group',
        'sub_total_after_expenses',
        'tag',
        'team_partnership_id',
        'tm_fees_due_to_chr',
        'total_gross_commission',
        'transaction_id',
        'zoho_aci_id'
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id', 'zoho_id');
    }

    public function chrAgent()
    {
        return $this->belongsTo(Contact::class, 'chr_agent_id', 'zoho_contact_id');
    }

    public function teamPartnership()
    {
        return $this->belongsTo(TeamAndPartnership::class, 'team_partnership_id');
    }

    public function transaction()
    {
        return $this->belongsTo(Deal::class, 'transaction_id', 'zoho_deal_id');
    }

    /**
     * Map Zoho data to ACI model attributes.
     *
     * @param array $data
     * @param string $source
     * @return array
     */
    public static function mapZohoData(array $data, string $source)
    {
        $mappedData = [];
        $idKey = $source === "webhook" ? $data['id'] : $data['Id'];
        $transactionId = $source === "webhook" ? $data['Transaction']['id'] : $data['Transaction'];
        $aciRecord = self::where('zoho_aci_id', $idKey)->first();
        $dealRecord = Deal::where('zoho_deal_id', $transactionId)->first();

        if (!$dealRecord) {
            Log::info("no deal record found for deal: $transactionId, skipping!");
            return [];
        }

        $mapField = function ($field, $sourceField = null) use ($data, $aciRecord, $source, &$mappedData) {
            if ($sourceField === null) {
                $sourceField = $field;
            }

            if (array_key_exists($sourceField, $data)) {
                $value = $data[$sourceField];

                // Handle various SQL field types
                if (in_array($field, ['double_ended', 'email_opt_out', 'np1', 'gt', 'personal_transaction'])) {
                    $mappedData[$field] = ($value === 'true' || $value === true || $value == 1) ? 1 : 0;
                } elseif (in_array($field, ['calculated_count', 'sides'])) {
                    $mappedData[$field] = is_numeric($value) ? (int) $value ?? null : null;
                } elseif (in_array($field, ['commission_percent', 'split_percent', 'portion_of_total', 'admin_fee_income', 'after_splits', 'agent_check_amount'])) {
                    $mappedData[$field] = ($value !== '') ? (float) $value : null;
                } elseif (in_array($field, ['closing_date'])) {
                    $mappedData[$field] = Carbon::parse($value)->toDateString();
                } elseif (in_array($field, ['created_at', 'updated_at'])) {
                    $mappedData[$field] = Carbon::parse($value)->toDateTimeString();
                } elseif ($source === 'webhook' && is_array($value) && isset($value['id'])) {
                    $mappedData[$field] = $value['id'];
                } else {
                    $mappedData[$field] = ($value !== '') ? $value : null;
                }
            } elseif ($aciRecord !== null) {
                $mappedData[$field] = $aciRecord->$field;
            }
        };

        $fieldsToMap = [
            'adjusted_gross_commission' => 'Adjusted_Gross_Commission',
            'admin_fee_income' => 'Admin_Fee_Income',
            'after_splits' => 'After_Splits',
            'agent_check_amount' => 'Agent_Check_Amount',
            'record_image' => 'Record_Image',
            'owner_id' => 'Owner.id',
            'agent_contribution_to_client_transaction_costs' => 'Agent_Contribution_to_Client_Transaction_Costs',
            'name' => 'Name',
            'agent_portion_of_commission_that_gets_split' => 'Agent_Portion_of_Commission_that_gets_split',
            'agent_team_for_capping' => 'Agent_Team_For_Capping',
            'chr_agent_id' => 'CHR_Agent.id',
            'calculated_count' => 'Calculated_Count',
            'calculated_gci' => 'Calculated_GCI',
            'calculated_volume' => 'Calculated_Volume',
            'chr_gives' => 'CHR_Gives',
            'chr_gives_due_to_chr' => 'CHR_Gives_due_to_CHR',
            'closing_date' => 'Closing_Date',
            'colorado_home_realty' => 'Colorado_Home_Realty',
            'commission_notes' => 'Commission_Notes',
            'commission_percent' => 'Commission_Percent',
            'credit_to_client' => 'Credit_to_Client',
            'currency' => 'Currency',
            'current_year' => 'Current_Year',
            'double_ended' => 'Double_Ended',
            'ecommission' => 'eCommission',
            'ecommission_payout' => 'eCommission_Payout',
            'email' => 'Email',
            'email_opt_out' => 'Email_Opt_Out',
            'exchange_rate' => 'Exchange_Rate',
            'gt' => 'GT',
            'home_warranty' => 'Home_Warranty',
            'home_warranty_payout' => 'Home_Warranty_Payout',
            'import_batch_id' => 'Import_Batch_ID',
            'irs_reported_1099_income_for_this_transaction' => 'IRS_Reported_1099_Income_For_This_Transaction',
            'less_initial_split_to_chr' => 'Less_Initial_Split_to_CHR',
            'less_residual_split_to_chr' => 'Less_Residual_Split_to_CHR',
            'less_split_to_chr' => 'Less_Split_to_CHR',
            'mentee_amount_paid' => 'Mentee_Amount_Paid',
            'np1' => 'NP1',
            'past_due_amount_to_chr' => 'Past_Due_Amount_to_CHR',
            'personal_transaction' => 'Personal_Transaction',
            'portion_of_total' => 'Portion_of_Total',
            'representing' => 'Representing',
            'sale_price' => 'Sale_Price',
            'secondary_email' => 'Secondary_Email',
            'sides' => 'Sides',
            'split_percent' => 'Split_Percent',
            'split_to_chr' => 'Split_to_CHR',
            'stage' => 'Stage',
            'strategy_group' => 'Strategy_Group',
            'sub_total_after_expenses' => 'Sub_Total_After_Expenses',
            'tag' => 'Tag',
            'team_partnership_id' => 'Team_Partnership.id',
            'tm_fees_due_to_chr' => 'TM_Fees_due_to_CHR',
            'total_gross_commission' => 'Total_Gross_Commission',
        ];

        foreach ($fieldsToMap as $field => $sourceField) {
            // Handle nested fields for webhook sources
            if (strpos($sourceField, '.') !== false) {
                $nestedFields = explode('.', $sourceField);
                $value = $data;
                foreach ($nestedFields as $nestedField) {
                    $value = $value[$nestedField] ?? null;
                }
                $mappedData[$field] = $value;
            } else {
                $mapField($field, $sourceField);
            }
        }

        $mappedData['transaction_id'] = $dealRecord->id;
        $mappedData['zoho_aci_id'] = $idKey;

        if ($dealRecord->teamPartnership) {
            Log::info("Team Partnership:" . $dealRecord->teamPartnership);
            $mappedData['team_partnership_id'] = $dealRecord->teamPartnership ?? null;
        }



        //Log::info("Mapped Data: ", ['data' => $mappedData]);

        return $mappedData;
    }
}
