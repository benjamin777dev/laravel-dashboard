<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'toEmail',
        'fromEmail',
        'subject',
        'content',
        
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
