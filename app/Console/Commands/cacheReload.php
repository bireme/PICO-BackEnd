<?php

namespace PICOExplorer\Console\Commands;

use Illuminate\Console\Command;
use Exception;
use Illuminate\Support\Facades\Artisan;

class cacheReload extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cache:reload';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'clears cache then autoloads it';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Artisan::call('config:clear', ["--force"=> true ]);
        Artisan::call('cache:clear', ["--force"=> true ]);
        Artisan::call('logs:clear', ["--force"=> true ]);
    }

}
