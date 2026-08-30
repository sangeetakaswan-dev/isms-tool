<?php

namespace App\Services\User;

use App\DTOs\UserData;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;

class UserService
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    ) {}

    public function updateProfile(int $userId, UserData $data): User
    {
        return $this->userRepository->updateProfile($userId, $data->toArray());
    }

    public function updateAvatar(int $userId, string $path): User
    {
        return $this->userRepository->updateAvatar($userId, $path);
    }

    public function deactivateUser(int $userId): void
    {
        $user = $this->userRepository->findOrFail($userId);
        $user->is_active = false;
        $user->save();
    }

    public function activateUser(int $userId): void
    {
        $user = $this->userRepository->findOrFail($userId);
        $user->is_active = true;
        $user->save();
    }

    public function updateLastLogin(User $user): void
    {
        $user->last_login_at = now();
        $user->save();
    }
}