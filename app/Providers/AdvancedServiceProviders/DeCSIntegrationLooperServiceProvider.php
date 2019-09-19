<?php

namespace PICOExplorer\Providers\AdvancedServiceProviders;

use Illuminate\Support\ServiceProvider;
use PICOExplorer\Services\DeCSIntegration\Looper\DeCSIntegrationLooperImporter;

class DeCSIntegrationLooperServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('decsintegrationlooper', function () {
            return new DeCSIntegrationLooperImporter();
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
