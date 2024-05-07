<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactGroups extends Model
{
    use HasFactory;

    protected $fillable = [
        'ownerId',
        'contactId',
        'groupId',
        'zoho_contact_group_id'
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
}
