<?php

namespace App\Providers;

use App\User;
use App\Post;
use App\Policies\PostPolicy;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Post::class => PostPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('update-post', 'App\Policies\PostPolicy@update');
        Gate::define('delete-post', 'App\Policies\PostPolicy@delete');
        Gate::define('approve-post-action', 'App\Policies\PostPolicy@approve');

        Gate::define('action-approve', function (User $user) {
            return $user->role_key == 'customer' ? true : false;
        });

        Passport::routes();
    }
}
