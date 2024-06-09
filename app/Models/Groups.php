<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class Groups extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'isPublic',
        'ownerId',
        'isABCD',
        'isShow',
        'zoho_group_id',
        'Owner_Name',
        'Owner_Id',
        'Owner_Email',
        'Modified_By_Name',
        'Modified_By_Id',
        'Modified_By_Email',
        'Created_By_Name',
        'Created_By_Id',
        'Created_By_Email',
        'Import_Code',
        'Display_Order',
        'isD',
        'Disable_Secondary_Access',
        'Secondary_Email',
        'Last_Activity_Time',
        'Currency',
        'Exchange_Rate',
        'Email_Opt_Out',
        'Layout',
        'Tag',
        'User_Modified_Time',
        'System_Modified_Time',
        'User_Related_Activity_Time',
        'System_Related_Activity_Time',
        'LAST_ACTION',
        'LAST_ACTION_TIME',
        'LAST_SENT_TIME',
        'Unsubscribed_Mode',
        'Unsubscribed_Time',
        'Record_Approval_Status',
        'Is_Record_Duplicate',
        'Record_Image',
        'Locked__s',
        'T',
        'created_at',
        'updated_at',
    ];

    public function ownerData()
    {
        return $this->belongsTo(User::class, 'ownerId');
    }

    public function contacts()
    {
        return $this->hasMany(ContactGroups::class, 'groupId');
    }

    /**
     * Map Zoho data to group model attributes.
     *
     * @param array $data
     * @return array
     */
    public static function mapZohoData(array $data, string $source)
    {
        // Initialize the mapped data array
        $mappedData = [];

        // Lookup the existing record in the database
        $idKey = $source == "webhook" ? $data['id'] : $data['Id'];
        Log::info("Found zoho group id: " . $idKey);

        $userGroup = self::where('zoho_group_id', $idKey)->first();
        if ($userGroup) {
            Log::info("--found an existing group for the group id: ". $idKey);
        }
        // Helper function to map fields
        $mapField = function ($field, $sourceField = null) use ($data, $userGroup, $source, &$mappedData) {
            if ($sourceField === null) {
                $sourceField = $field;
            }

            // Handle boolean fields specifically
            $booleanFields = ['isPublic', 'isABCD', 'isShow', 'isD', 'Disable_Secondary_Access', 'Email_Opt_Out', 'Is_Record_Duplicate', 'Locked__s', 'T'];
            if (in_array($field, $booleanFields)) {
                if (array_key_exists($sourceField, $data)) {
                    $mappedData[$field] = ($data[$sourceField] === 'true' || $data[$sourceField] === true || $data[$sourceField] == 1) ? 1 : 0;
                } elseif ($userGroup !== null) {
                    $mappedData[$field] = $userGroup->$field;
                }
            } elseif (in_array($field, ['Display_Order', 'Exchange_Rate'])) {
                // Handle int and float/double fields
                if (array_key_exists($sourceField, $data)) {
                    $mappedData[$field] = is_numeric($data[$sourceField]) ? $data[$sourceField] : null;
                } elseif ($userGroup !== null) {
                    $mappedData[$field] = $userGroup->$field;
                }
            } else {
                // Handle date/time fields
                if (array_key_exists($sourceField, $data)) {
                    if (in_array($field, ['Last_Activity_Time', 'User_Modified_Time', 'System_Modified_Time', 'User_Related_Activity_Time', 'System_Related_Activity_Time', 'LAST_ACTION_TIME', 'LAST_SENT_TIME', 'Unsubscribed_Time', 'created_at', 'updated_at'])) {
                        $mappedData[$field] = Carbon::parse($data[$sourceField])->toDateTimeString();
                    } else {
                        // Handle JSON objects vs. simple values
                        if ($source === 'webhook' && is_array($data[$sourceField]) && isset($data[$sourceField]['id'])) {
                            $mappedData[$field] = $data[$sourceField]['id'];
                        } else {
                            $mappedData[$field] = $data[$sourceField];
                        }
                    }
                } elseif ($userGroup !== null) {
                    $mappedData[$field] = $userGroup->$field;
                }
            }
        };

        // Map the fields
        $mapField('name', 'Name');
        $mapField('isPublic', 'Is_Public');
        $mapField('Owner_Id', 'Owner');
        $mapField('isABCD', 'isABC');
        $mapField('isD', 'isD');
        $mapField('Owner_Name', 'Owner.name');
        $mapField('Owner_Email', 'Owner.email');
        $mapField('Modified_By_Name', 'Modified_By.name');
        $mapField('Modified_By_Id', 'Modified_By');
        $mapField('Modified_By_Email', 'Modified_By.email');
        $mapField('Created_By_Name', 'Created_By.name');
        $mapField('Created_By_Id', 'Created_By');
        $mapField('Created_By_Email', 'Created_By.email');
        $mapField('Import_Code', 'Import_Code');
        $mapField('Display_Order', 'Display_Order');
        $mapField('Disable_Secondary_Access', 'Disable_Secondary_Access');
        $mapField('Secondary_Email', 'Secondary_Email');
        $mapField('Last_Activity_Time', 'Last_Activity_Time');
        $mapField('Currency', 'Currency');
        $mapField('Exchange_Rate', 'Exchange_Rate');
        $mapField('Email_Opt_Out', 'Email_Opt_Out');
        $mapField('Layout', 'Layout');
        $mapField('Tag', 'Tag');
        $mapField('User_Modified_Time', 'User_Modified_Time');
        $mapField('System_Modified_Time', 'System_Modified_Time');
        $mapField('User_Related_Activity_Time', 'User_Related_Activity_Time');
        $mapField('System_Related_Activity_Time', 'System_Related_Activity_Time');
        $mapField('LAST_ACTION', 'LAST_ACTION');
        $mapField('LAST_ACTION_TIME', 'LAST_ACTION_TIME');
        $mapField('LAST_SENT_TIME', 'LAST_SENT_TIME');
        $mapField('Unsubscribed_Mode', 'Unsubscribed_Mode');
        $mapField('Unsubscribed_Time', 'Unsubscribed_Time');
        $mapField('Record_Approval_Status', 'Record_Approval_Status');
        $mapField('Is_Record_Duplicate', 'Is_Record_Duplicate');
        $mapField('Record_Image', 'Record_Image');
        $mapField('Locked__s', 'Locked__s');
        $mapField('T', 'T');

        $mappedData['zoho_group_id'] = $idKey;
        $zOwner = $source == "webhook" ? $data["Owner"]['id'] : $data['Owner'];
        $eUser = User::where("root_user_id", $zOwner)->first();

        if ($eUser) {
            $mappedData['ownerId'] = $eUser->id;
        } else {
            $mappedData['ownerId'] = $zOwner;
        }

        Log::info("Mapped Data: ", ['data' => $mappedData]);

        return $mappedData;
    }

}
