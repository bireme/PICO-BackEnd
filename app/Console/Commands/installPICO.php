<?php

namespace PICOExplorer\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Composer;
use Illuminate\Support\Facades\Artisan;
use PICOExplorer\Services\AdvancedLogger\Exceptions\DontCatchException;

class installPICO extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'installPICO';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'reinstalls after update';

    /**
     * The Composer instance.
     *
     * @var Composer
     */
    protected $composer;


    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Composer $composer)
    {
        parent::__construct();

        $this->composer = $composer;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        try {
            echo PHP_EOL . '1. clearing cache... ';
            Artisan::call('cache:clear');
            echo 'Success';
        } catch (DontCatchException $ex) {
            echo 'Failed';
        }
        try {
            echo PHP_EOL . '2. clearing config cache... ';
            Artisan::call('config:clear');
            echo 'Success';
        } catch (DontCatchException $ex) {
            echo 'Failed';
        }
        try {
            echo PHP_EOL . '3. Autoloading dump... ';
            $this->composer->dumpAutoloads();
            $this->composer->dumpOptimized();
            echo 'Success';
        } catch (DontCatchException $ex) {
            echo 'Failed';
        }
        try {
            echo PHP_EOL . '4. clearing views... ';
            Artisan::call('view:clear');
            echo 'Success';
        } catch (DontCatchException $ex) {
            echo 'Failed';
        }
        try {
            echo PHP_EOL . '5. clearing routes... ';
            Artisan::call('route:clear');
            echo 'Success';
        } catch (DontCatchException $ex) {
            echo 'Failed';
        }
        try {
            echo PHP_EOL . '6. Generating ide helper...';
            Artisan::call('ide-helper:generate');
            echo 'Success';
        } catch (DontCatchException $ex) {
            echo 'Failed';
        }
    }


}
