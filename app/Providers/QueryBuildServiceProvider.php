<?php

namespace PICOExplorer\Providers;

use Illuminate\Support\ServiceProvider;
use PICOExplorer\Services\QueryBuild\QueryBuildProcess;

class QueryBuildServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('querybuild', function () {
            return new QueryBuildProcess();
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
