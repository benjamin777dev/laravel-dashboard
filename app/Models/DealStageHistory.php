<?php

namespace App\Models;

use App\Models\Deal;
use Illuminate\Database\Eloquent\Model;

class DealStageHistory extends Model
{
    // Define the table name if it does not follow Laravel's naming convention
    protected $table = 'deal_stage_histories';

    // Fillable fields for mass assignment
    protected $fillable = [
        'zoho_id', // The unique ID for each stage history entry
        'zoho_deal_id', // Foreign key linking to deals table
        'stage', // Stage of the deal (e.g., Active, Under Contract)
        'modified_time', // Time when the stage was modified
        'stage_duration', // Duration the deal stayed in the stage
        'amount', // Deal amount
        'closing_date', // Closing date of the deal
        'currency', // Currency type
        'exchange_rate', // Exchange rate for the deal
        'expected_revenue', // Expected revenue from the deal
        'last_activity_time', // Last activity time
        'moved_to', // Next stage moved to
        'probability' // Probability percentage
    ];

    // Cast certain fields to their appropriate data types
    protected $casts = [
        'modified_time' => 'datetime',
        'closing_date' => 'date',
        'last_activity_time' => 'datetime',
        'amount' => 'decimal:2',
        'expected_revenue' => 'decimal:2',
        'exchange_rate' => 'decimal:2',
        'probability' => 'integer'
    ];

    /**
     * Relationship: A deal stage history belongs to a deal
     */
    public function deal()
    {
        return $this->belongsTo(Deal::class, 'zoho_deal_id', 'zoho_deal_id');
    }
}
