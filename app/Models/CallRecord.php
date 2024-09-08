<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CallRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "contact_id",
        "phone_number",
        "start_time"
    ];

    public function contact()
    {
        return $this->belongsTo(Contact::class, "contact_id");
    }

    public function user()
    {
        return $this->belongsTo(User::class, "user_id");
    }
}