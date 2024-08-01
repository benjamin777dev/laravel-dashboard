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
        'ccEmail',
        'bccEmail',
        'fromEmail',
        'subject',
        'content',
        'userId',
        'isEmailSent',
        'isDeleted',
        'sendEmailFrom',
        'message_id'
    ];

    public function fromUserData()
    {
        return $this->belongsTo(User::class, 'fromEmail');
    }
    public function dealData()
    {
        return $this->belongsTo(Deal::class, 'dealId');
    }
}
