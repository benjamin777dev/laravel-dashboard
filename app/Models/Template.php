<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'ownerId',
        'subject',
        'active',
        'favorite',
        'consent_linked',
        'associated',
        'folder',
        'templateType',
        'content',
        'zoho_template_id'
    ];
}
