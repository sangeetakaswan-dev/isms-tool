<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckTenantPermission
{
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        $user = Auth::user();
        $tenantId = $user->current_tenant_id;

        if (!$tenantId) {
            abort(403, 'No tenant selected.');
        }

        $hasPermission = $user->tenants()
            ->where('tenant_id', $tenantId)
            ->where(function ($query) use ($permission) {
                $query->whereJsonContains('permissions', $permission)
                    ->orWhere('role', 'owner');
            })
            ->exists();

        if (!$hasPermission) {
            abort(403, 'You do not have permission to perform this action.');
        }

        return $next($request);
    }
}