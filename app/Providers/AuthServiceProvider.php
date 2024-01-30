<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Policies\AdminComponentPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    protected $providers = [
        'users' => [
            'driver' => 'eloquent',
            'model' => \App\Models\tbltraineeaccount::class,
        ],
    ];



    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        Gate::define('authorizeAdminComponents', [AdminComponentPolicy::class, 'authorizeComponent']);
        Gate::define('authorizeBillingModule', [AdminComponentPolicy::class, 'restrictBillingModule']);
    }
}
