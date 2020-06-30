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

Route::post('login', 'API\UserController@login');
Route::post('register', 'API\UserController@register');

Route::middleware(['auth:api'])->group(function () {
    Route::get('categories', 'API\CategoriesController@categories');
    Route::post('checkin', 'API\CheckinController@checkin');
    Route::get('hotels', 'API\HotelController@hotels');
    Route::get('rooms', 'API\RoomController@rooms');
    Route::post('logout', 'API\UserController@logout');
});


