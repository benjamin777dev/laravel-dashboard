<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Contact;
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

    protected $casts = [
        'toEmail' => 'array', // Cast toEmail as an array
    ];
    public function fromUserData()
    {
        return $this->belongsTo(Contact::class, 'fromEmail');
    }

    public function getToUserDataAttribute()
    {
        return Contact::whereIn('id', $this->toEmail)->get();
    }
    public function dealData()
    {
        return $this->belongsTo(Deal::class, 'dealId');
    }
}
