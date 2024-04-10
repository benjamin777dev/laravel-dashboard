<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aci extends Model
{
    use HasFactory;

    protected $fillable = [
        'closing_date',
        'current_year',
        'agent_check_amount',
        'userId',
        'irs_reported_1099_income_for_this_transaction',
        'stage',
        'total',
        'zoho_aci_id',
        'dealId',
        'less_split_to_chr',
        'agentName'
    ];

    public function userData()
    {
        return $this->belongsTo(User::class, 'userId');
    }
    public function dealData()
    {
        return $this->belongsTo(Deal::class, 'dealId');
    }
}
