<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class ContactGroups extends Model
{
    use HasFactory;

    protected $fillable = [
        'ownerId',
        'contactId',
        'groupId',
        'zoho_contact_group_id',
        'modified_time',
        'email',
        'created_time',
        'name',
        'last_activity_time',
        'import_batch',
        'secondary_email',
        'email_opt_out',
        'modified_by_id',
        'modified_by_name',
        'created_by_id',
        'created_by_name',
        'contacts_id',
        'contacts_name',
        'groups_id',
        'groups_name'
    ];

    public static function getZohoContactInfo()
    {
        // Retrieve Zoho contact ID, last name, and first name
        return self::select('zoho_contact_id', 'last_name', 'first_name')->get();
    }

    public function contactData()
    {
        return $this->belongsTo(Contact::class, 'contactId');
    }

    public function userData()
    {
        return $this->belongsTo(User::class, 'ownerId');
    }

    public function groups()
    {
        return $this->hasMany(ContactGroups::class, 'contactId')->with("group");
    }

    public function group()
    {
        return $this->belongsTo(Groups::class, 'groupId');
    }

    public function groupData()
    {
        return $this->belongsTo(Groups::class, 'groupId');
    }

    public function modifiedBy()
    {
        return $this->belongsTo(User::class, 'modified_by_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function contact()
    {
        return $this->belongsTo(Contact::class, 'contacts_id');
    }

    public static function mapZohoData(array $data, string $source)
    {
        // Initialize the mapped data array
        $mappedData = [];

        // Determine the ID key based on the source
        $idKey = $source === "webhook" ? $data['id'] : $data['Id'];

        // Lookup the existing record in the database
        $contactGroup = self::where('zoho_contact_group_id', $idKey)->first();

        // Helper function to map fields
        $mapField = function ($field, $sourceField = null) use ($data, $contactGroup, $source, &$mappedData) {
            if ($sourceField === null) {
                $sourceField = $field;
            }

            // Handle boolean fields specifically
            $booleanFields = ['email_opt_out'];
            if (in_array($field, $booleanFields)) {
                if (array_key_exists($sourceField, $data)) {
                    $mappedData[$field] = ($data[$sourceField] === 'true' || $data[$sourceField] === true || $data[$sourceField] == 1) ? 1 : 0;
                } elseif ($contactGroup !== null) {
                    $mappedData[$field] = $contactGroup->$field;
                }
            } else {
                // Handle date/time fields
                if (array_key_exists($sourceField, $data)) {
                    if (in_array($field, ['last_activity_time', 'created_time', 'modified_time'])) {
                        $mappedData[$field] = Carbon::parse($data[$sourceField])->toDateTimeString();
                    } else {
                        // Handle JSON objects vs. simple values
                        if ($source === 'webhook' && is_array($data[$sourceField]) && isset($data[$sourceField]['id'])) {
                            $mappedData[$field] = $data[$sourceField]['id'];
                        } else {
                            $mappedData[$field] = $data[$sourceField];
                        }
                    }
                } elseif ($contactGroup !== null) {
                    $mappedData[$field] = $contactGroup->$field;
                }
            }
        };

        // Map the fields
        $fieldsToMap = [
            'name' => 'Name',
            'email' => 'Email',
            'created_time' => 'Created_Time',
            'modified_time' => 'Modified_Time',
            'last_activity_time' => 'Last_Activity_Time',
            'import_batch' => 'Import_Batch',
            'secondary_email' => 'Secondary_Email',
            'email_opt_out' => 'Email_Opt_Out'
        ];

        foreach ($fieldsToMap as $field => $sourceField) {
            $mapField($field, $sourceField);
        }

        if ($source === "webhook") {
            $webhookFieldsToMap = [
                'modified_by_id' => 'Modified_By.id',
                'modified_by_name' => 'Modified_By.name',
                'created_by_id' => 'Created_By.id',
                'created_by_name' => 'Created_By.name',
                'contacts_id' => 'Contacts.id',
                'contacts_name' => 'Contacts.name',
                'groups_id' => 'Groups.id',
                'groups_name' => 'Groups.name'
            ];
        } else {
            $webhookFieldsToMap = [
                'modified_by_id' => 'Modified_By',
                'created_by_id' => 'Created_By',
                'contacts_id' => 'Contacts',
                'groups_id' => 'Groups'
            ];
        }

        foreach ($webhookFieldsToMap as $field => $sourceField) {
            $mapField($field, $sourceField);
        }

        // Handle contact and group lookups
        $foundUser = isset($mappedData['contacts_id']) 
            ? Contact::where('zoho_contact_id', $mappedData['contacts_id'])->first() 
            : null;
        $foundGroup = isset($mappedData['groups_id']) 
            ? Groups::where('zoho_group_id', $mappedData['groups_id'])->first() 
            : null;

        // Set contactId and groupId based on lookups
        $mappedData['contactId'] = $foundUser ? $foundUser->id : $mappedData['contacts_id'] ?? null;
        $mappedData['groupId'] = $foundGroup ? $foundGroup->id : $mappedData['groups_id'] ?? null;

        $mappedData['zoho_contact_group_id'] = $idKey;

        // Handle owner lookup
        $zOwner = $source === "webhook" ? $data["Owner"]['id'] : $data['Owner'];
        $eUser = User::where("root_user_id", $zOwner)->first();

        $mappedData['ownerId'] = $eUser ? $eUser->id : $zOwner;

        //Log::info("Mapped Data: ", ['data' => $mappedData]);

        return $mappedData;
    }

}
