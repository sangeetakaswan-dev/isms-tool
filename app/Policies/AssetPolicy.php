<?php

namespace App\Policies;

use App\Models\Asset;
use App\Models\User;

class AssetPolicy
{
    // Sab dekh sakte hain
    public function viewAny(User $user): bool
    {
        return true;
    }

    // Tenant isolation check
    public function view(User $user, Asset $asset): bool
    {
        return $user->current_tenant_id === $asset->tenant_id;
    }

    // Sirf owner, admin, assessor create kar sakte hain
    public function create(User $user): bool
    {
        $role = $user->roleInCurrentTenant();
        return in_array($role, ['owner', 'admin', 'assessor']);
    }

    // Sirf owner, admin, assessor edit kar sakte hain
    public function update(User $user, Asset $asset): bool
    {
        if ($user->current_tenant_id !== $asset->tenant_id)
            return false;
        $role = $user->roleInCurrentTenant();
        return in_array($role, ['owner', 'admin', 'assessor']);
    }

    // Sirf owner aur admin delete kar sakte hain
    public function delete(User $user, Asset $asset): bool
    {
        if ($user->current_tenant_id !== $asset->tenant_id)
            return false;
        $role = $user->roleInCurrentTenant();
        return in_array($role, ['owner', 'admin']);
    }
}