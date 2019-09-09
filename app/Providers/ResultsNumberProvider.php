<?php

namespace PICOExplorer\Providers;

use Illuminate\Support\ServiceProvider;
use PICOExplorer\Services\ResultsNumber\ResultsNumberProcess;

class ResultsNumberProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('resultsnumberprocess', function () {
            return new ResultsNumberProcess();
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
