<?php

namespace PICOExplorer\Providers;

use Illuminate\Support\ServiceProvider;
use PICOExplorer\Services\AdvancedLogger\AdvancedLogger;
use PICOExplorer\Services\AdvancedLogger\Exceptions\Handlers\WarningsHandler;
use PICOExplorer\Services\AdvancedLogger\Services\ExceptionLogger;
use PICOExplorer\Services\AdvancedLogger\Services\ServicePerformance;
use PICOExplorer\Services\AdvancedLogger\Services\SpecialValidator;
use PICOExplorer\Services\AdvancedLogger\Services\AdvancedTimer;
use PICOExplorer\Services\AdvancedLogger\Services\UltraLogger;

class AdvancedLoggerProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('advancedlogger', function () {
            return new AdvancedLogger();
        });
        $this->app->singleton('warningshandler', function () {
            return new WarningsHandler();
        });
        $this->app->singleton('exceptionlogger', function () {
            return new ExceptionLogger();
        });
        $this->app->singleton('specialvalidator', function () {
            return new SpecialValidator();
        });
        $this->app->singleton('ultralogger', function () {
            return new UltraLogger();
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
