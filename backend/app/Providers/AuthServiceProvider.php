<?php

namespace App\Providers;

use App\Policies\CoursePolicy;
use App\Models\Courses;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Les mappages de politiques pour l'application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        Courses::class => CoursePolicy::class,

    ];

    /**
     * Enregistrer les services d'authentification et d'autorisation.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //definir les gates
        Gate::define('isAdmin', function($user){
            return $user->role=== 'administrator';
        });
    }
}
