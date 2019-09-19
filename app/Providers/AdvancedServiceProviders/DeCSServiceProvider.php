<?php

namespace PICOExplorer\Providers\AdvancedServiceProviders;

use Illuminate\Support\ServiceProvider;
use PICOExplorer\Services\DeCS\DeCSProcess;

class DeCSServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('decs', function () {
            return new DeCSProcess();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
