<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        '/bulkJob/update', 
        '/webhook/contact',
        '/webhook/deal',
        '/webhook/contact_group',
        '/webhook/task',
        '/webhook/aci',
        '/webhook/note',
        '/api/webhook/csvcallback',
        '/itlm/*',
        '/shared/*'
    ];
}
