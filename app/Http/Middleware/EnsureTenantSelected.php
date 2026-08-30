<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureTenantSelected
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user && !$user->current_tenant_id) {
            // Allow tenant setup routes
            if ($request->routeIs('tenant.setup', 'tenant.store', 'tenants.*')) {
                return $next($request);
            }

            // For API requests
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'No organization selected. Please select an organization.',
                ], 403);
            }

            // Redirect to tenant setup
            return redirect()->route('tenant.setup')
                ->with('warning', 'Please select or create an organization to continue.');
        }

        return $next($request);
    }
}