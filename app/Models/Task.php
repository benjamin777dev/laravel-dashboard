<?php
namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'closed_time',
        'who_id',
        'created_by',
        'currency',
        'description',
        'due_date',
        'exchange_rate',
        'import_batch',
        'modified_by',
        'priority',
        'what_id',
        'status',
        'subject',
        'owner',
        'zoho_task_id',
        'created_time',
        'related_to'
    ];

    protected $casts = [
        'closed_time' => 'datetime',
        'due_date' => 'date',
        'created_time' => 'date',
    ];

    public function who()
    {
        return $this->belongsTo(Contact::class, 'who_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function modifiedBy()
    {
        return $this->belongsTo(User::class, 'modified_by');
    }

    public function dealData()
    {
        return $this->belongsTo(Deal::class, 'what_id','id');
    }
    public function contactData()
    {
        return $this->belongsTo(Contact::class, 'who_id','id');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner');
    }

    public static function mapZohoData(array $data, string $source)
    {
        $mappedData = [];

        $idKey = $source === "webhook" ? $data['id'] : $data['Id'];
        $task = self::where('zoho_task_id', $idKey)->first();

        // Helper function to map boolean fields
        $mapBooleanField = function ($field, $sourceField) use ($data, $task, &$mappedData) {
            if (array_key_exists($sourceField, $data)) {
                $mappedData[$field] = ($data[$sourceField] === 'true' || $data[$sourceField] === true || $data[$sourceField] == 1) ? 1 : 0;
            } elseif ($task !== null) {
                $mappedData[$field] = $task->$field;
            }
        };

        // Helper function to map fields
        $mapField = function ($field, $sourceField = null) use ($data, $task, $source, &$mappedData) {
            $sourceField = $sourceField ?? $field;

            if (in_array($field, ['closed_time', 'created_time', 'modified_time', 'due_date'])) {
                if (array_key_exists($sourceField, $data)) {
                    $mappedData[$field] = Carbon::parse($data[$sourceField])->toDateTimeString();
                } elseif ($task !== null) {
                    $mappedData[$field] = $task->$field;
                }
            } else {
                if (array_key_exists($sourceField, $data)) {
                    $mappedData[$field] = is_array($data[$sourceField]) && isset($data[$sourceField]['id']) ? $data[$sourceField]['id'] : $data[$sourceField];
                } elseif ($task !== null) {
                    $mappedData[$field] = $task->$field;
                }
            }

            if ($field === 'what_id' && empty($mappedData[$field])) {
                $mappedData[$field] = null;
            }
        };

        // Fields to map
        $fieldsToMap = [
            'closed_time' => 'Closed_Time',
            'who_id' => 'Who_Id',
            'created_by' => 'Created_By',
            'currency' => 'Currency',
            'description' => 'Description',
            'due_date' => 'Due_Date',
            'exchange_rate' => 'Exchange_Rate',
            'import_batch' => 'Import_Batch',
            'modified_by' => 'Modified_By',
            'priority' => 'Priority',
            'what_id' => 'What_Id',
            'status' => 'Status',
            'subject' => 'Subject',
            'owner' => 'Owner',
            'created_time' => 'Created_Time',
            'related_to' => 'Related_To'
        ];

        foreach ($fieldsToMap as $field => $sourceField) {
            $mapField($field, $sourceField);
        }

        $webhookFieldsToMap = [
            'modified_by_id' => $source === "webhook" ? 'Modified_By.id' : 'Modified_By',
            'created_by_id' => $source === "webhook" ? 'Created_By.id' : 'Created_By',
            'owner_id' => $source === "webhook" ? 'Owner.id' : 'Owner'
        ];

        foreach ($webhookFieldsToMap as $field => $sourceField) {
            $mapField($field, $sourceField);
        }

        // Handle contact and deal lookups
        $foundWho = isset($mappedData['who_id']) ? Contact::where('zoho_contact_id', $mappedData['who_id'])->first() : null;
        $foundWhat = isset($mappedData['what_id']) ? Deal::where('zoho_deal_id', $mappedData['what_id'])->first() : null;

        $mappedData['who_id'] = $foundWho ? $foundWho->id : null;
        $mappedData['what_id'] = $foundWhat ? $foundWhat->id : null;

        if (!is_null($mappedData['who_id']) && !is_null($mappedData['what_id'])) {
            $mappedData['related_to'] = "Both";
        } elseif (!is_null($mappedData['who_id'])) {
            $mappedData['related_to'] = "Contacts";
        } elseif (!is_null($mappedData['what_id'])) {
            $mappedData['related_to'] = "Deals";
        } else {
            $mappedData['related_to'] = null; // or handle the case where neither is set
        }

        $mappedData['zoho_task_id'] = $idKey;

        // Handle owner lookup
        $zOwner = $source === "webhook" ? $data["Owner"]['id'] : $data['Owner'];
        $eUser = User::where("root_user_id", $zOwner)->first();
        $mappedData['owner'] = $eUser ? $eUser->id : $zOwner;

       

        return $mappedData;
    }
}
