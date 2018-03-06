<?php

namespace App\Providers;

use App\Contact;
use App\Department;
use App\Expense;
use App\Lead;
use App\LeadService;
use App\Message;
use App\User;
use App\Policies\ContactPolicy;
use App\Policies\DepartmentPolicy;
use App\Policies\ExpensePolicy;
use App\Policies\LeadPolicy;
use App\Policies\MessagePolicy;
use App\Policies\ServicePolicy;
use App\Policies\TaskPolicy;
use App\Policies\UserPolicy;
use App\Task;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Contact::class => ContactPolicy::class,
        Lead::class => LeadPolicy::class,
        Task::class => TaskPolicy::class,
        Expense::class => ExpensePolicy::class,
        Message::class => MessagePolicy::class,
        LeadService::class => ServicePolicy::class,
        Department::class => DepartmentPolicy::class,
		User::class => UserPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @param GateContract $gate
     */
    public function boot(GateContract $gate)
    {
        $this->registerPolicies($gate);
    }
}
