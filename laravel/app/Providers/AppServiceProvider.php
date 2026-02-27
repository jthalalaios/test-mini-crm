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
        Company::observe(CompanyObserver::class);

        // Share $languages with the main layout
        View::composer('layouts.app', function ($view) {
            $languages = Language::where('enabled', true)->get();
            $view->with('languages', $languages);   
        });
    }
}
