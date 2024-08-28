<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactRole extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'sequence_no',
        'userId',
        'zoho_role_id',
    ];

    public function userData()
    {
        return $this->belongsTo(User::class, 'userId');
    }
}


