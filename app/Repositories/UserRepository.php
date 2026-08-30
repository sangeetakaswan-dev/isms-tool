<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function model(): string
    {
        return User::class;
    }

    public function getAll(array $filters = []): Collection
    {
        $query = $this->model->query();

        if (isset($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        if (isset($filters['is_active'])) {
            $query->where('is_active', $filters['is_active']);
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
                    ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        if (isset($filters['is_active'])) {
            $query->where('is_active', $filters['is_active']);
        }

        return $query->orderBy('name')->paginate($perPage);
    }

    public function findByEmail(string $email): ?User
    {
        return $this->model->where('email', $email)->first();
    }

    public function getUsersByTenant(int $tenantId): Collection
    {
        return $this->model
            ->whereHas('tenants', function ($q) use ($tenantId) {
                $q->where('tenants.id', $tenantId);
            })
            ->orderBy('name')
            ->get();
    }

    public function getActiveUsers(): Collection
    {
        return $this->model->where('is_active', true)->get();
    }

    public function updateProfile(int $userId, array $data): User
    {
        $user = $this->findOrFail($userId);
        $user->update($data);
        return $user;
    }

    public function updateAvatar(int $userId, string $path): User
    {
        $user = $this->findOrFail($userId);
        $user->avatar_path = $path;
        $user->save();
        return $user;
    }
}