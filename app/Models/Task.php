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
        'recurring_activity',
        'status',
        'subject',
        'tag',
        'owner',
    ];

    protected $casts = [
        'closed_time' => 'datetime',
        'due_date' => 'date',
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

    public function what()
    {
        return $this->belongsTo(Task::class, 'what_id');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner');
    }
}
