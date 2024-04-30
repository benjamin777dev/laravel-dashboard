<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;

    protected $fillable = [
        'owner',
        'related_to',
        'related_to_parent_record_id',
        'related_to_module_id',
        'note_content',
        'mark_as_done',
        'created_time',
        'zoho_note_id',
        'related_to_type'
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
}
