<?php

namespace App\Providers;

use App\Repositories\Contracts\AssessmentRepositoryInterface;
use App\Repositories\Contracts\ControlRepositoryInterface;
use App\Repositories\Contracts\TenantRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\AssessmentRepository;
use App\Repositories\ControlRepository;
use App\Repositories\TenantRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(TenantRepositoryInterface::class, TenantRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(AssessmentRepositoryInterface::class, AssessmentRepository::class);
        $this->app->bind(ControlRepositoryInterface::class, ControlRepository::class);
    }

    public function boot(): void
    {
        //
    }
}