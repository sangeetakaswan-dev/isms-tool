<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureTenantSelected
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Agar current_tenant_id null hai
        if (is_null($user->current_tenant_id)) {
            // Check karo user ke paas koi tenant hai
            $firstTenant = $user->tenants()->first();

            if ($firstTenant) {
                // Auto-attach first tenant
                $user->update(['current_tenant_id' => $firstTenant->id]);
            } else {
                // Koi tenant nahi hai — gracefull redirect to tenant setup
                if (!$request->routeIs('tenants.*') && !$request->routeIs('profile.*') && !$request->routeIs('logout')) {
                    return redirect()
                        ->route('tenants.setup')
                        ->with('warning', 'Welcome! Please create your first organization to get started.');
                }
            }
        }

        return $next($request);
    }
}