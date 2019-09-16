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

Route::middleware(['throttle:60,1'])->prefix('PICO')->group(function () {

    Route::post('DeCSExplore', 'PICO\DeCSController@index');

    Route::post('ResultsNumber', 'PICO\ResultsNumberController@index');

    Route::post('QueryBuild', 'PICO\QueryBuildController@index');

    Route::post('DeCSIntegration', 'PICO\IntegrationDeCSController@index');

    Route::post('ResultsNumberIntegration', 'PICO\IntegrationResultsNumberController@index');

    Route::get('DeCSExplore', 'PICO\DeCSController@info');

    Route::get('ResultsNumber', 'PICO\ResultsNumberController@info');

    Route::get('QueryBuild', 'PICO\QueryBuildController@info');

    Route::get('DeCSIntegration', 'PICO\IntegrationDeCSController@infoPICO\IntegrationDeCSController@info');

    Route::get('ResultsNumberIntegration', 'PICO\IntegrationResultsNumberController@info');
});

Route::group(['prefix' => 'admin', 'middleware' => ['throttle:60,1']], function () {
    Route::get('login', [
        'as' => 'auth.adminlogin.login',
        'uses' => 'Admin\AdminLogin@showLogin'
    ]);
    Route::post('auth', [
        'as' => 'auth.adminlogin.auth',
        'uses' => 'Admin\AdminLogin@doLogin'
    ]);
    Route::get('logout', [
        'as' => 'auth.adminlogin.logout',
        'uses' => 'Admin\AdminLogin@doLogout'
    ]);
    Route::get('/', function () {
        return redirect('admin/home');
    });
    Route::group(['middleware' => ['IsAdmin']], function () {
        Route::get('home', 'Admin\CustomLogController@index');
        Route::get('test', 'Test\TestController@index');
        Route::post('dd', 'Test\ddController@savePreviousInfo');
        Route::get('dd', 'Test\ddController@index');
    });
});

Route::get('{lang}', ['uses' => 'LanguageController@switchLang'])->middleware('throttle:60,1');
Route::post('{lang}', ['uses' => 'LanguageController@savePreviousInfo'])->middleware('throttle:60,1');
Route::get('/', function () {
    $lang = App::getLocale();
    return redirect($lang);
})->middleware('throttle:60,1');

