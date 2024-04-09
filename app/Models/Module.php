<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory;
    protected $fillable = [
        'zoho_module_id',
        'modified_time',
        'api_name',
    ];

    public static function getApiName()
    {
        return self::pluck('api_name')->toArray();
    }
}
