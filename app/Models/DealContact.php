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
        'contactRole',
    ];

    public function contactData()
    {
        return $this->belongsTo(Contact::class, 'contactId');
    }
}
