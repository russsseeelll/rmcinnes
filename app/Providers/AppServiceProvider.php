<?php

namespace App\Providers;

use App\Models\Deploy;
use App\Models\Profile;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Every page reads the site owner's details from the database.
        View::composer('*', fn ($view) => $view->with('profile', Profile::current()));
        View::composer('layouts.app', fn ($view) => $view->with('latestDeploy', Deploy::latest('deployed_at')->first()));
    }
}
