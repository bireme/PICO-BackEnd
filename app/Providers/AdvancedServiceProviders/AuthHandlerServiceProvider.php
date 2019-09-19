<?php

namespace PICOExplorer\Providers\AdvancedServiceProviders;

use Illuminate\Support\ServiceProvider;
use PICOExplorer\Services\AuthHandler\AuthHandler;

class AuthHandlerServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('authhandler', function () {
            return new AuthHandler();
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
