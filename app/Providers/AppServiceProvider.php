<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;
use App\Models\Users;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // $this->app->bind('path.public', function () {
        //     return base_path() . '\public_html';
        // });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Gate::define('for-admin', function (Users $user) {
            return $user->admin == 1;
        });

        Gate::define('for-admin-ketim', function (Users $user) {
            return $user->admin == 1 || $user->admin == 2;
        });
    }
}
