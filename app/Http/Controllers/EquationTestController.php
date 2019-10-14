<?php

namespace PICOExplorer\Http\Controllers;

use Config;
use Exception;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use AdvancedLoggerFacade;
use Tests\Feature\EqTester;
use Tests\Unit\PruebaUnit;

class EquationTestController extends Controller
{

    public function tester()
    {
        $test = new EqTester();
        $test->EquationTests();
    }

}
