<?php

namespace PICOExplorer\Providers\AdvancedServiceProviders;

use Illuminate\Support\ServiceProvider;
use PICOExplorer\Services\ResultsNumberIntegration\Looper\ResultsNumberIntegrationLooperImporter;

class ResultsNumberIntegrationLooperServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('resultsnumberintegrationlooper', function () {
            return new ResultsNumberIntegrationLooperImporter();
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
