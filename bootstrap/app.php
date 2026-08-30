<?php

use App\Http\Middleware\CheckTenantPermission;
use App\Http\Middleware\EnsureTenantSelected;
use App\Http\Middleware\ValidateTenantAccess;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Register middleware aliases
        $middleware->alias([
            'tenant.selected' => EnsureTenantSelected::class,
            'tenant.permission' => CheckTenantPermission::class,
            'tenant.access' => ValidateTenantAccess::class,
        ]);
    })

    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
