<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ValidateTenantAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        $tenantId = $user->current_tenant_id;

        if (!$tenantId) {
            abort(403, 'No tenant selected.');
        }

        $hasAccess = $user->tenants()
            ->where('tenant_id', $tenantId)
            ->exists();

        if (!$hasAccess) {
            abort(403, 'You do not have access to this tenant.');
        }

        return $next($request);
    }
}