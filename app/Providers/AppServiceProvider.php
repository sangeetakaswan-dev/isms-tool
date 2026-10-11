<?php

namespace App\Providers;

use App\Repositories\AssessmentRepository;
use App\Repositories\AssessmentResponseRepository;
use App\Repositories\Contracts\AssessmentRepositoryInterface;
use App\Repositories\Contracts\AssessmentResponseRepositoryInterface;
use App\Repositories\Contracts\ControlRepositoryInterface;
use App\Repositories\Contracts\TenantRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\ControlRepository;
use App\Repositories\TenantRepository;
use App\Repositories\UserRepository;
use App\Models\Assessment;
use App\Policies\AssessmentTeamPolicy;
use Illuminate\Support\Facades\Gate;
use App\Models\Risk;
use App\Policies\RiskPolicy;
use Illuminate\Support\ServiceProvider;
use App\Repositories\Reporting\Contracts\ReportRepositoryInterface;
use App\Repositories\Reporting\ReportRepository;
use App\Repositories\Contracts\RiskRepositoryInterface;
use App\Repositories\RiskRepository;





class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(TenantRepositoryInterface::class, TenantRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(AssessmentRepositoryInterface::class, AssessmentRepository::class);
        $this->app->bind(ControlRepositoryInterface::class, ControlRepository::class);
        $this->app->bind(AssessmentResponseRepositoryInterface::class, AssessmentResponseRepository::class);
        $this->app->bind(
            \App\Repositories\Contracts\SoAEntryRepositoryInterface::class,
            \App\Repositories\SoAEntryRepository::class
        );
        $this->app->bind(
            \App\Repositories\Contracts\SoAVersionRepositoryInterface::class,
            \App\Repositories\SoAVersionRepository::class
        );
        $this->app->bind(
            \App\Repositories\Contracts\DocumentRepositoryInterface::class,
            \App\Repositories\DocumentRepository::class
        );

        $this->app->bind(
            ReportRepositoryInterface::class,
            ReportRepository::class
        );

        $this->app->bind(RiskRepositoryInterface::class, RiskRepository::class);
        
    }

    public function boot(): void
    {
        Gate::policy(Assessment::class, AssessmentTeamPolicy::class);
        Gate::policy(Risk::class, RiskPolicy::class);
    }

}