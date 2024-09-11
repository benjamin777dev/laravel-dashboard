<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecordedMedia extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'uuid',
        'file_name',
        's3path'
    ];

}
