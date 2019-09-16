<?php


namespace PICOExplorer\Http\Controllers\Admin;

use PragmaRX\Health\Http\Controllers\Health;
use View;

class CustomHealthController extends Health
{

    public function Health(){
        return View('vendor/pragmarx/health/html')->with('laravel', ['health' => config('health')]);
    }

}
