<?php

namespace App\Policies;

use App\Models\Risk;
use App\Models\User;

class RiskPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->current_tenant_id !== null;
    }

    public function view(User $user, Risk $risk): bool
    {
        return $user->current_tenant_id === $risk->tenant_id;
    }

    public function create(User $user): bool
    {
        return in_array($user->roleInCurrentTenant(), ['owner', 'admin', 'assessor']);
    }

    public function update(User $user, Risk $risk): bool
    {
        if ($user->current_tenant_id !== $risk->tenant_id) return false;
        return in_array($user->roleInCurrentTenant(), ['owner', 'admin', 'assessor']);
    }

    public function delete(User $user, Risk $risk): bool
    {
        if ($user->current_tenant_id !== $risk->tenant_id) return false;
        return in_array($user->roleInCurrentTenant(), ['owner', 'admin']);
    }

    public function assignOwner(User $user, Risk $risk): bool
    {
        if ($user->current_tenant_id !== $risk->tenant_id) return false;
        return in_array($user->roleInCurrentTenant(), ['owner', 'admin']);
    }
}   