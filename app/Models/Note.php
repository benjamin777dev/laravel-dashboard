<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;


class Note extends Model
{
    use HasFactory;


    protected $fillable = [
        'owner', // Owner -> 2 (relates to Users->id)
        'related_to', // Parent_id.module.api_name ($contact->id or $user->id)
        'related_to_parent_record_id', // Parent_Id // id of the record from zoho
        'related_to_module_id', // $se_module // id of the module from zoho
        'note_content', // Note_Content // blah blah blah
        'mark_as_done', // boolean (0 is false, 1 is true)
        'created_time', // Created_Time
        'zoho_note_id', // Id // id from zoho note id
        'related_to_type' // Contact or Deal
    ];

    public function userData()
    {
        return $this->belongsTo(User::class, 'owner');
    }

    public function dealData()
    {
        return $this->belongsTo(Deal::class, 'related_to');
    }

    public function moduleData()
    {
        return $this->belongsTo(Module::class, 'related_to_module_id', 'zoho_module_id');
    }

    public function ContactData()
    {
        return $this->belongsTo(Contact::class, 'related_to');
    }
    public function taskData()
    {
        return $this->belongsTo(Task::class, 'related_to');
    }


    /**
     * Map Zoho data to note model attributes.
     *
     * @param array $data
     * @return array
     */
    public static function mapZohoData(array $data, string $source)
    {
        $mappedData = [];
        $idKey = $source === "webhook" ? $data['id'] : $data['Id'];
        $existingNote = self::where('zoho_note_id', $idKey)->first();
        
        $module_name = $source === "webhook" 
            ? $data['Parent_Id']['module']['api_name'] 
            : $data['Parent_Id.module.api_name'] ?? '';

        // Map the module to its id from modules
        $moduleInfo = Module::where('api_name', $module_name)->first();
        if (!$moduleInfo) {
            Log::info("no module found with api name: $module_name");
            return [];
        }
        
        // Find the owner
        $ownerId = $source === "webhook" ? $data['Owner']['id'] : $data['Owner'];
        $owner = User::where('root_user_id', $ownerId)->first();
        if (!$owner) {
            Log::info("Unable to find an owner for this note, skipping import! $ownerId");
            return [];
        }

        // Map parent and related to type
        $parentId = $source === "webhook" ? $data['Parent_Id']['id'] : $data['Parent_Id'];
        $related_to = null;
        $related_to_type = '';
        
        if (isset($parentId)) {
            switch ($module_name) {
                case 'Deals':
                    $related_to = Deal::where('zoho_deal_id', $parentId)->first();
                    $related_to_type = 'Deal';
                    
                    break;
                case 'Contacts':
                    $related_to = Contact::where('zoho_contact_id', $parentId)->first();
                    $related_to_type = 'Contact';
                    break;
                case 'Tasks':
                    $related_to = Task::where('zoho_task_id', $parentId)->first();
                    $related_to_type = 'Tasks';
                    break;
                default:
                    Log::info("Invalid Note Type: $module_name");
                    return [];
            }

            if (!$related_to) {
                Log::info("Unable to find a $related_to_type with id of $parentId, skipping import!");
                return [];
            }
        }

        // Mapping fields
        $mappedData = [
            'owner' => $owner->id,
            'related_to' => $related_to ? $related_to->id : null,
            'related_to_module_id' => $moduleInfo ? $moduleInfo->zoho_module_id : null,
            'related_to_parent_record_id' => $parentId,
            'note_content' => $data['Note_Content'] ?? '',
            'created_time' => Carbon::parse($data['Created_Time'])->setTimezone('UTC')->format('Y-m-d H:i:s'),
            'zoho_note_id' => $idKey,
            'related_to_type' => $related_to_type,
            'mark_as_done' => $existingNote->mark_as_done ?? false
        ];

        
        return $mappedData;
    }


}
