<?php

namespace PICOExplorer\Providers\AdvancedServiceProviders;

use Illuminate\Support\ServiceProvider;
use PICOExplorer\Services\DeCSIntegration\DeCSIntegrationProcess;

class DeCSIntegrationServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('decsintegration', function () {
            return new DeCSIntegrationProcess();
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
