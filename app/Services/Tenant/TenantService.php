<?php

namespace App\Services\Tenant;

use App\DTOs\TenantData;
use App\Models\Tenant;
use App\Repositories\Contracts\TenantRepositoryInterface;
use Illuminate\Support\Facades\Log;

class TenantService
{
    public function __construct(
        private readonly TenantRepositoryInterface $tenantRepository
    ) {}

    public function createTenant(TenantData $data, int $ownerId): Tenant
    {
        try {
            $tenant = $this->tenantRepository->createWithOwner($data->toArray(), $ownerId);
            
            Log::info('Tenant created', [
                'tenant_id' => $tenant->id,
                'owner_id' => $ownerId,
            ]);

            return $tenant;
        } catch (\Exception $e) {
            Log::error('Failed to create tenant', [
                'error' => $e->getMessage(),
                'data' => $data->toArray(),
            ]);
            throw $e;
        }
    }

    public function updateTenant(int $tenantId, TenantData $data): Tenant
    {
        $tenant = $this->tenantRepository->findOrFail($tenantId);
        $tenant->update($data->toArray());
        
        return $tenant;
    }

    public function addMember(Tenant $tenant, int $userId, string $role): void
    {
        $this->tenantRepository->addMember($tenant, $userId, $role);
        
        Log::info('Member added to tenant', [
            'tenant_id' => $tenant->id,
            'user_id' => $userId,
            'role' => $role,
        ]);
    }

    public function removeMember(Tenant $tenant, int $userId): void
    {
        $this->tenantRepository->removeMember($tenant, $userId);
        
        Log::info('Member removed from tenant', [
            'tenant_id' => $tenant->id,
            'user_id' => $userId,
        ]);
    }

    public function updateMemberRole(Tenant $tenant, int $userId, string $role): void
    {
        $this->tenantRepository->updateMemberRole($tenant, $userId, $role);
    }
}