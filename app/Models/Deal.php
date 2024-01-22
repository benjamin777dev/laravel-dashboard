<?php
// app/Models/Deal.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deal extends Model
{
    use HasFactory;

    protected $fillable = [
        'deal_name',
        'owner_id',
        'amount',
        'stage',
        'closing_date',
        'description',
        'lead_source',
        'contact_name_id',
        'account_name',
        'probability',
        'next_step',
        'expected_revenue',
        'type'
    ];

    /**
     * Relationship with User model.
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Relationship with Contact model.
     * Assumes a Contact model exists and has an id field.
     */
    public function contact()
    {
        return $this->belongsTo(Contact::class, 'contact_name_id');
    }
}
