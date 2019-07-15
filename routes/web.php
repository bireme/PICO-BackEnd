<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    $lang=App::getLocale();
    return redirect($lang);
});

Route::middleware(['throttle:60,1'])->prefix('PICO')->group(function () {

    Route::post('DeCSExplore', 'MainControllers\DeCSController@index');

    Route::post('ResultsNumber', 'MainControllers\ResultsNumberController@index');

    Route::post('QueryBuild', 'MainControllers\QueryBuildController@index');

    Route::post('DeCSIntegration', 'MainControllers\IntegrationDeCSController@index');

    Route::post('ResultsNumberIntegration', 'MainControllers\IntegrationResultsNumberController@index');

    Route::get('DeCSExplore', 'MainControllers\DeCSController@info');

    Route::get('ResultsNumber', 'MainControllers\ResultsNumberController@info');

    Route::get('QueryBuild', 'MainControllers\QueryBuildController@info');

    Route::get('DeCSIntegration', 'MainControllers\IntegrationDeCSController@info');

    Route::get('ResultsNumberIntegration', 'MainControllers\IntegrationResultsNumberController@info');
});

Route::get('{lang}', ['uses'=>'LanguageController@switchLang']);

Route::post('{lang}', ['uses'=>'LanguageController@savePreviousInfo']);

Route::get('admin/logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');

Route::get('admin/test', 'Test\TestController@index');

Route::post ('admin/dd', 'Test\ddController@savePreviousInfo');
Route::get('admin/dd', 'Test\ddController@index');
