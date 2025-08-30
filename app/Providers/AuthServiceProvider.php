<?php

namespace App\Providers;

use App\Models\Transaction;
use App\Models\Account;
use App\Policies\TransactionPolicy;
use App\Policies\AccountPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Transaction::class => TransactionPolicy::class,
        Account::class => AccountPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}
