<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * Indicates whether the XSRF-TOKEN cookie should be set on the response.
     *
     * @var bool
     */
    protected $addHttpCookie = true;

    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
       'http://proizvodnja.duplico.hr/equipment_lists',
       'http://proizvodnja.duplico.hr/android',
       'https://proizvodnja.duplico.hr/android',
       'http://localhost:8000/android'
    ];
}
