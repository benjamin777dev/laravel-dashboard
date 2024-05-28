<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DealContact extends Model
{
    use HasFactory;
    protected $fillable = [
        'zoho_deal_id',
        'contactId',
        'userId',
        'contactRole',
    ];

    public function contactData()
    {
        return $this->belongsTo(Contact::class, 'contactId');
    }
    public function userData()
    {
        return $this->belongsTo(User::class, 'userId');
    }
    public function roleData()
    {
        return $this->belongsTo(ContactRole::class, 'contactRole', 'zoho_role_id');
    }
}
