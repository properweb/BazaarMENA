<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::prefix('user')
    ->name('user.')
    ->group(function () {
        Route::post('/sign-up', 'UserController@signUp')->name('signUp');
        Route::post('/login', 'UserController@login')->name('login');
    });
Route::middleware('auth:api')
    ->prefix('user')
    ->name('user.')
    ->group(function () {
        Route::get('/get-user-details', 'UserController@getUserDetails')->name('getUserDetails');
    });



