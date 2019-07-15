<?php

namespace PICOExplorer\Console\Commands;

use Illuminate\Console\Command;
use Exception;

class logsClear extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'logs:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete all logs';

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

        $folder = 'storage/logs/';
        echo PHP_EOL . 'Exploring folder: '.$folder;
        $files = glob($folder.'*.log');
        $this->DeleteFilesInFolder($folder);

        $folders = glob('storage/logs/*');
        foreach ($folders as $folder) {
            $this->DeleteFilesInFolder($folder);
        }
    }

    protected function DeleteFilesInFolder($folder){
        echo PHP_EOL . 'Exploring folder: ' . $folder;
        $files = glob($folder . '/*.log');
        $count = 0;
        $negcount = 0;
        echo ' (' . count($files) . ' files)';
        foreach ($files as $file) {
            try {
                \File::delete($file);
                $count++;
            } catch (Exception $e) {
                $negcount++;
            }
        }
        echo PHP_EOL . '  -->Removed  ' . $count . ' files';
        if ($negcount) {
            echo PHP_EOL . '  -->Failed to remove  ' . $negcount . ' files';
        }
    }

}
