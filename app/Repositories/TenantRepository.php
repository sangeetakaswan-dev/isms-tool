<?php

namespace App\Repositories;

use App\Models\Tenant;
use App\Models\User;
use App\Repositories\Contracts\TenantRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class TenantRepository extends BaseRepository implements TenantRepositoryInterface
{
    public function model(): string
    {
        return Tenant::class;
    }

    public function getAll(array $filters = []): Collection
    {
        $query = $this->model->query();

        if (isset($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('contact_email', 'LIKE', "%{$search}%");
            });
        }

        return $query->orderBy('name')->get();
    }

    public function getPaginated(int $perPage = 15, array $filters = []): LengthAwarePaginator
    {
        $query = $this->model->query();

        if (isset($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('contact_email', 'LIKE', "%{$search}%");
            });
        }

        return $query->orderBy('name')->paginate($perPage);
    }

    public function findBySlug(string $slug): ?Tenant
    {
        return $this->model->where('slug', $slug)->first();
    }

    public function getUserTenants(int $userId): Collection
    {
        return $this->model
            ->whereHas('users', function ($query) use ($userId) {
                $query->where('users.id', $userId);
            })
            ->get();
    }

    public function createWithOwner(array $data, int $userId): Tenant
    {
        return DB::transaction(function () use ($data, $userId) {
            $tenant = $this->create($data);

            $tenant->users()->attach($userId, [
                'role' => 'owner',
            ]);

            $user = User::find($userId);
            $user->current_tenant_id = $tenant->id;
            $user->save();

            return $tenant;
        });
    }

    public function getTenantMembers(Tenant $tenant): Collection
    {
        return $tenant->users()->get();
    }

    public function addMember(Tenant $tenant, int $userId, string $role): void
    {
        $tenant->users()->attach($userId, [
            'role' => $role,
        ]);
    }

    public function removeMember(Tenant $tenant, int $userId): void
    {
        $tenant->users()->detach($userId);
    }

    public function updateMemberRole(Tenant $tenant, int $userId, string $role): void
    {
        $tenant->users()->updateExistingPivot($userId, [
            'role' => $role,
        ]);
    }
}