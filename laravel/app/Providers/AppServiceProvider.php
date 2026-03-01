<?php

namespace App\Providers;

use App\Models\Language;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\Company;
use App\Observers\CompanyObserver;

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
    public function boot()
    {
        // Only register CompanyObserver if not running a seeder or migration
        if (!$this->app->runningInConsole() || !$this->isSeedingOrMigrating()) {
            Company::observe(CompanyObserver::class);
        }

        // Share $languages with the main layout
        View::composer('layouts.app', function ($view) {
            $languages = Language::where('enabled', true)->get();
            $view->with('languages', $languages);   
        });
    }

    /**
     * Determine if the application is running a seeder or migration.
     */
    protected function isSeedingOrMigrating(): bool
    {
        $argv = $_SERVER['argv'] ?? [];
        return collect($argv)->contains(function ($arg) {
            return str_contains($arg, 'db:seed') || str_contains($arg, 'migrate');
        });
    }
}
