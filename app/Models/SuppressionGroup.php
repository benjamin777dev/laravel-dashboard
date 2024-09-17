<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuppressionGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        "regular_emails",
        "video_emails"
    ];

    public static function isSuppressed($userId, $emailType)
    {
        $suppression = self::where('user_id', $userId)->first();

        if (!$suppression) {
            return false;
        }

        if ($emailType === 'regular') {
            return $suppression->regular_emails;
        } elseif ($emailType === 'video') {
            return $suppression->video_emails;  
        }

        return false;
    }

}
