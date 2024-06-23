<?php

namespace App\Providers;

use App\Models\GeneralSetting;
use App\Models\LogoSetting;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Config;
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
        Paginator::useBootstrapFive();

        $generalSettings = GeneralSetting::first();
        $logoSetting = LogoSetting::first();

        /** set time zone */
        Config::set('app.timezone', $generalSettings->time_zone);

        /** Share variable at all views */
        View::composer('*', function ($view) use ($generalSettings, $logoSetting) {
            $view->with(['settings' => $generalSettings, 'logoSetting' => $logoSetting]);
        });
    }
}
