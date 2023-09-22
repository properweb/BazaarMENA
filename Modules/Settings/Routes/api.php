<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')
    ->prefix('settings')
    ->name('settings.')
    ->group(function () {
        Route::post('/update/change-password', 'SettingsController@changePassword')->name('changePassword');
        Route::post('/update/info', 'SettingsController@updateInfo')->name('updateInfo');
        Route::post('/update/company-profile-info', 'SettingsController@updateCompanyProfileInfo')->name('updateCompanyProfileInfo');
        Route::post('/update/shipping-update-or-create', 'SettingsController@shippingUpdateOrCreate')->name('shippingUpdateOrCreate');
    });