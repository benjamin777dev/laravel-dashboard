<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BulkJob extends Model
{
    use HasFactory;

    protected $fillable = [
        'userId',
        'jobId',
        'fileId'
    ];

    public function userData()
    {
        return $this->belongsTo(User::class, 'userID');
    }
}
