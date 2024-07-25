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
        $mappedData = [];
        $idKey = $source === "webhook" ? $data['id'] : $data['Id'];
        $userGroup = self::where('zoho_group_id', $idKey)->first();

        $mapField = function ($field, $sourceField = null) use ($data, $userGroup, $source, &$mappedData) {
            if ($sourceField === null) {
                $sourceField = $field;
            }

            $booleanFields = ['isPublic', 'isABCD', 'isShow', 'isD', 'Disable_Secondary_Access', 'Email_Opt_Out', 'Is_Record_Duplicate', 'Locked__s', 'T'];
            if (in_array($field, $booleanFields)) {
                if (array_key_exists($sourceField, $data)) {
                    $mappedData[$field] = ($data[$sourceField] === 'true' || $data[$sourceField] === true || $data[$sourceField] == 1) ? 1 : 0;
                } elseif ($userGroup !== null) {
                    $mappedData[$field] = $userGroup->$field;
                }
            } elseif (in_array($field, ['Display_Order', 'Exchange_Rate'])) {
                if (array_key_exists($sourceField, $data)) {
                    $mappedData[$field] = is_numeric($data[$sourceField]) ? $data[$sourceField] : null;
                } elseif ($userGroup !== null) {
                    $mappedData[$field] = $userGroup->$field;
                }
            } else {
                if (array_key_exists($sourceField, $data)) {
                    if (in_array($field, ['Last_Activity_Time', 'User_Modified_Time', 'System_Modified_Time', 'User_Related_Activity_Time', 'System_Related_Activity_Time', 'LAST_ACTION_TIME', 'LAST_SENT_TIME', 'Unsubscribed_Time', 'created_at', 'updated_at'])) {
                        $mappedData[$field] = Carbon::parse($data[$sourceField])->toDateTimeString();
                    } else {
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

        $fieldsToMap = [
            'name' => 'Name',
            'isPublic' => 'Is_Public',
            'isABCD' => 'isABC',
            'isD' => 'isD',
            'Import_Code' => 'Import_Code',
            'Display_Order' => 'Display_Order',
            'Disable_Secondary_Access' => 'Disable_Secondary_Access',
            'Secondary_Email' => 'Secondary_Email',
            'Last_Activity_Time' => 'Last_Activity_Time',
            'Currency' => 'Currency',
            'Exchange_Rate' => 'Exchange_Rate',
            'Email_Opt_Out' => 'Email_Opt_Out',
            'Layout' => 'Layout',
            'Tag' => 'Tag',
            'User_Modified_Time' => 'User_Modified_Time',
            'System_Modified_Time' => 'System_Modified_Time',
            'User_Related_Activity_Time' => 'User_Related_Activity_Time',
            'System_Related_Activity_Time' => 'System_Related_Activity_Time',
            'LAST_ACTION' => 'LAST_ACTION',
            'LAST_ACTION_TIME' => 'LAST_ACTION_TIME',
            'LAST_SENT_TIME' => 'LAST_SENT_TIME',
            'Unsubscribed_Mode' => 'Unsubscribed_Mode',
            'Unsubscribed_Time' => 'Unsubscribed_Time',
            'Record_Approval_Status' => 'Record_Approval_Status',
            'Is_Record_Duplicate' => 'Is_Record_Duplicate',
            'Record_Image' => 'Record_Image',
            'Locked__s' => 'Locked__s',
            'T' => 'T'
        ];

        foreach ($fieldsToMap as $field => $sourceField) {
            $mapField($field, $sourceField);
        }

        if ($source === "webhook") {
            $nestedFieldsToMap = [
                'Owner_Id' => 'Owner.id',
                'Owner_Name' => 'Owner.name',
                'Owner_Email' => 'Owner.email',
                'Modified_By_Name' => 'Modified_By.name',
                'Modified_By_Id' => 'Modified_By.id',
                'Modified_By_Email' => 'Modified_By.email',
                'Created_By_Name' => 'Created_By.name',
                'Created_By_Id' => 'Created_By.id',
                'Created_By_Email' => 'Created_By.email'
            ];
        } else {
            $nestedFieldsToMap = [
                'Owner_Id' => 'Owner',
                'Modified_By_Id' => 'Modified_By',
                'Created_By_Id' => 'Created_By'
            ];
        }

        foreach ($nestedFieldsToMap as $field => $sourceField) {
            if ($source === "webhook") {
                $sourceFieldParts = explode('.', $sourceField);
                $value = $data;
                foreach ($sourceFieldParts as $part) {
                    if (isset($value[$part])) {
                        $value = $value[$part];
                    } else {
                        $value = null;
                        break;
                    }
                }
                $mappedData[$field] = $value;
            } else {
                $mappedData[$field] = $data[$sourceField] ?? null;
            }
        }

        $mappedData['zoho_group_id'] = $idKey;

        $zOwner = $source === "webhook" ? $data["Owner"]['id'] : $data['Owner'];
        $eUser = User::where("root_user_id", $zOwner)->first();

        $mappedData['ownerId'] = $eUser ? $eUser->id : $zOwner;

        Log::info("Mapped Data: ", ['data' => $mappedData]);

        return $mappedData;
    }


}
