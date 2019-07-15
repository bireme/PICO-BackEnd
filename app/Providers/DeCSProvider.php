<?php

namespace PICOExplorer\Providers;

use Illuminate\Support\ServiceProvider;
use PICOExplorer\Services\DeCS\DeCSProcess;

class DeCSProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('decsprocess', function () {
            return new DeCSProcess();
        });
        $this->app->bind('DeCSQueryProcessor', 'App\Services\DeCS\ControllerDeCSQueryProcessor');
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
