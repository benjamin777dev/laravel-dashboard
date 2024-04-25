<?php

namespace App\Models;

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

    public function dealdata()
    {
        return $this->belongsTo(Deal::class, 'what_id');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner');
    }
}
