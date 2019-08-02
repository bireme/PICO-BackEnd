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

    Route::post('DeCSExplore', 'PICO\DeCSController@index');

    Route::post('ResultsNumber', 'PICO\ResultsNumberController@index');

    Route::post('QueryBuild', 'PICO\QueryBuildController@index');

    Route::post('DeCSIntegration', 'PICO\IntegrationDeCSController@index');

    Route::post('ResultsNumberIntegration', 'PICO\IntegrationResultsNumberController@index');

    Route::get('DeCSExplore', 'PICO\DeCSController@info');

    Route::get('ResultsNumber', 'PICO\ResultsNumberController@info');

    Route::get('QueryBuild', 'PICO\QueryBuildController@info');

    Route::get('DeCSIntegration', 'PICO\IntegrationDeCSController@info');

    Route::get('ResultsNumberIntegration', 'PICO\IntegrationResultsNumberController@info');
});

Route::get('{lang}', ['uses'=>'LanguageController@switchLang']);

Route::post('{lang}', ['uses'=>'LanguageController@savePreviousInfo']);

Route::get('admin/logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');

Route::get('admin/test', 'Test\TestController@index');

Route::post ('admin/dd', 'Test\ddController@savePreviousInfo');
Route::get('admin/dd', 'Test\ddController@index');
