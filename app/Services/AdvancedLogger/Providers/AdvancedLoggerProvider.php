<?php

namespace PICOExplorer\Providers;

use Illuminate\Support\ServiceProvider;
use PICOExplorer\Services\AdvancedLogger\AdvancedLogger;
use PICOExplorer\Services\AdvancedLogger\Exceptions\Handlers\WarningsHandler;
use PICOExplorer\Services\AdvancedLogger\Services\ExceptionLogger;
use PICOExplorer\Services\AdvancedLogger\Services\ServicePerformance;
use PICOExplorer\Services\AdvancedLogger\Services\SpecialValidator;
use PICOExplorer\Services\AdvancedLogger\Services\TimerService;

class AdvancedLoggerProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('advancedlogger', function () {
            return new AdvancedLogger();
        });
        $this->app->bind('warningshandler', function () {
            return new WarningsHandler();
        });
        $this->app->bind('timerservice', function () {
            return new TimerService();
        });
        $this->app->bind('exceptionlogger', function () {
            return new ExceptionLogger();
        });
        $this->app->bind('specialvalidator', function () {
            return new SpecialValidator();
        });
        $this->app->bind('serviceperformance', function () {
            return new ServicePerformance();
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
