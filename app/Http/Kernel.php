<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    protected $middlewareAliases = [
        // ... other middleware
        'tenant.selected' => \App\Http\Middleware\EnsureTenantSelected::class,
        'tenant.permission' => \App\Http\Middleware\CheckTenantPermission::class,
        'tenant.access' => \App\Http\Middleware\ValidateTenantAccess::class,
    ];

    protected $middlewareGroups = [
        'web' => [
            // ... other middleware
        ],

        'api' => [
            // ... other middleware
        ],
    ];
}