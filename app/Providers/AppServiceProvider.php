<?php

namespace PICOExplorer\Providers;

use Illuminate\Support\ServiceProvider;
use PICOExplorer\Services\AuthHandler\AuthHandler;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

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
