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

Route::prefix('selection')
    ->name('selection.')
    ->group(function () {
        Route::get('/get-categories', 'SelectionController@getCategory')->name('getCategory');
        Route::get('/get-countries', 'SelectionController@getCountries')->name('getCountries');
        Route::get('/get-country-by-id/{id}', 'SelectionController@getCountryById')->name('getCountryById');
        Route::get('/get-state/{country_id}', 'SelectionController@getState')->name('getState');
        Route::get('/get-state-by-id/{id}', 'SelectionController@getStateById')->name('getStateById');
        Route::get('/get-city/{state_id}', 'SelectionController@getCity')->name('getCity');
        Route::get('/get-city-by-id/{id}', 'SelectionController@getCityById')->name('getCityById');
    });