<?php

namespace App\Models;

use App\Models\Contact;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

/**
 * Team and Partnership Model
 */
class TeamAndPartnership extends Model
{
    use HasFactory;

    protected $table = 'teams_and_partnerships';

    protected $primaryKey = 'team_partnership_id'; // Specify the primary key

    public $incrementing = false; // Since team_partnership_id is not auto-incrementing

    protected $keyType = 'string'; // If the primary key is a string

    protected $fillable = [
        'created_by',
        'created_date',
        'currency',
        'email',
        'email_opt_out',
        'exchange_rate',
        'members',
        'modified_by',
        'secondary_email',
        'tag',
        'team_cap',
        'team_created_by',
        'team_lead',
        'team_or_partner_image',
        'team_or_partner_owner',
        'team_profile',
        'team_partnership_id',
        'name',
        'last_activity_time',
        'layout',
        'user_modified_time',
        'system_modified_time',
    ];

    protected $casts = [
        'members' => 'array',
        'created_date' => 'datetime',
        'last_activity_time' => 'datetime',
        'user_modified_time' => 'datetime',
        'system_modified_time' => 'datetime',
    ];

    /**
     * Check if a user is a team agent
     *
     * @param integer $userId
     * @return boolean
     */
    public static function isTeamAgent($userId)
    {
        return self::whereJsonContains('members', [['id' => $userId]])->exists();
    }

    /**
     * Get the team associated with a specific user ID
     *
     * @param integer $userId
     * @return TeamAndPartnership|null
     */
    public static function getTeamFromUserId($userId)
    {
        return self::whereJsonContains('members', [['id' => $userId]])->first();
    }

    /**
     * Get a specific team by its ID
     *
     * @param integer $teamId
     * @return TeamAndPartnership|null
     */
    public static function getTeam($teamId)
    {
        return self::find($teamId);
    }

    /**
     * Get the members of a specific team
     *
     * @param integer $teamId
     * @return array|null
     */
    public static function getTeamMembers($teamId)
    {
        $team = self::find($teamId);
        return $team ? json_decode($team->members, true) : null;
    }

    /**
     * Check if a user is a member of a specific team
     *
     * @param integer $userId
     * @param integer $teamId
     * @return boolean
     */
    public static function isMemberOfTeam($userId, $teamId)
    {
        $team = self::find($teamId);
        if ($team) {
            $members = collect(json_decode($team->members, true));
            return $members->contains('id', $userId);
        }
        return false;
    }

    /**
     * Get the team profile associated with this team
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function getTeamProfile()
    {
        return $this->belongsTo(Contact::class, 'team_profile', 'zoho_contact_id');
    }

    /**
     * Get the user who is the owner of this team
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ownerData()
    {
        return $this->belongsTo(User::class, 'team_or_partner_owner', 'root_user_id');
    }

    /**
     * Map Zoho data to team and partnership model attributes.
     *
     * @param array $data
     * @param string $source
     * @return array
     */
    public static function mapZohoData(array $data, string $source)
    {
        $mappedData = [];

        $idKey = $source == "webhook" ? $data['id'] : $data['Id'];
        Log::info("Found zoho team and partnership id: " . $idKey);

        $teamAndPartnership = self::where('team_partnership_id', $idKey)->first();

        $mapField = function ($field, $sourceField = null) use ($data, $teamAndPartnership, $source, &$mappedData) {
            if ($sourceField === null) {
                $sourceField = $field;
            }

            $booleanFields = ['email_opt_out', 'is_record_duplicate', 'locked__s'];
            if (in_array($field, $booleanFields)) {
                if (array_key_exists($sourceField, $data)) {
                    $mappedData[$field] = ($data[$sourceField] === 'true' || $data[$sourceField] === true || $data[$sourceField] == 1) ? 1 : 0;
                } elseif ($teamAndPartnership !== null) {
                    $mappedData[$field] = $teamAndPartnership->$field;
                }
            } elseif (in_array($field, ['exchange_rate', 'team_cap'])) {
                if (array_key_exists($sourceField, $data)) {
                    $mappedData[$field] = is_numeric($data[$sourceField]) ? $data[$sourceField] : null;
                } elseif ($teamAndPartnership !== null) {
                    $mappedData[$field] = $teamAndPartnership->$field;
                }
            } elseif (in_array($field, ['created_date', 'last_activity_time', 'user_modified_time', 'system_modified_time', 'created_time', 'modified_time', 'unsubscribed_time', 'last_action_time', 'last_sent_time'])) {
                if (array_key_exists($sourceField, $data)) {
                    $mappedData[$field] = Carbon::parse($data[$sourceField])->toDateTimeString();
                } elseif ($teamAndPartnership !== null) {
                    $mappedData[$field] = $teamAndPartnership->$field;
                }
            } else {
                if (array_key_exists($sourceField, $data)) {
                    if ($source === 'webhook' && is_array($data[$sourceField]) && isset($data[$sourceField]['id'])) {
                        $mappedData[$field] = $data[$sourceField]['id'];
                    } else {
                        $mappedData[$field] = $data[$sourceField];
                    }
                } elseif ($teamAndPartnership !== null) {
                    $mappedData[$field] = $teamAndPartnership->$field;
                }
            }
        };

        $mapField('created_by', 'Created_By');
        $mapField('created_date', 'Created_Time');
        $mapField('currency', 'Currency');
        $mapField('email', 'Email');
        $mapField('email_opt_out', 'Email_Opt_Out');
        $mapField('exchange_rate', 'Exchange_Rate');
        $mapField('modified_by', 'Modified_By');
        $mapField('secondary_email', 'Secondary_Email');
        $mapField('tag', 'Tag');
        $mapField('team_cap', 'Team_CAP');
        $mapField('team_created_by', 'Team_Created_By');
        $mapField('team_lead', 'Primary_Contact');
        $mapField('team_or_partner_image', 'Record_Image');
        $mapField('team_or_partner_owner', 'Owner');
        $mapField('team_profile', 'Team_Profile');
        $mapField('team_partnership_id', 'Id');
        $mapField('name', 'Name');
        $mapField('last_activity_time', 'Last_Activity_Time');
        $mapField('layout', 'Layout');
        $mapField('user_modified_time', 'User_Modified_Time');
        $mapField('system_modified_time', 'System_Modified_Time');

        $mappedData['team_partnership_id'] = $idKey;
        $zOwner = $source == "webhook" ? $data["Owner"]['id'] : $data['Owner'];
        $eUser = User::where("root_user_id", $zOwner)->first();

        if ($eUser) {
            $mappedData['team_or_partner_owner'] = $eUser->id;
        } else {
            $mappedData['team_or_partner_owner'] = $zOwner;
        }

        // Populate members field during the mappedData building
        $contacts = Contact::where('team_partnership', $idKey)->get(['zoho_contact_id', 'id']);
        $members = $contacts->map(function ($contact) {
            return ['zoho_contact_id' => $contact->zoho_contact_id, 'id' => $contact->id];
        });

        $mappedData['members'] = $members->toJson();

        Log::info("Mapped Data: ", ['data' => $mappedData]);

        return $mappedData;
    }
}
