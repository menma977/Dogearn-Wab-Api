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
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Authorization');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE');

Route::post('login', 'Api\UserController@login');
Route::post('/store', 'Api\UserController@store')->name('store');

Route::middleware('auth:api')->group(function () {

  Route::group(['prefix' => 'user', 'as' => 'user.'], static function () {
    Route::get('/show', 'Api\UserController@show')->name('show');
    Route::get('/edit', 'Api\UserController@edit')->name('edit');
    Route::post('/update', 'Api\UserController@update')->name('update');
    Route::post('/send/doge', 'Api\UserController@sendDoge')->name('sendDoge');
    Route::get('/on/grade', 'Api\UserController@isUserGradeProcess')->name('isUserGradeProcess');
  });

  Route::group(['prefix' => 'grade', 'as' => 'grade.'], static function () {
    Route::get('/create', 'Api\GradeHistoryController@create')->name('create');
    Route::post('/store', 'Api\GradeHistoryController@store')->name('store');
    Route::get('/show', 'Api\GradeHistoryController@show')->name('show');
    Route::post('/update', 'Api\GradeHistoryController@update')->name('update');
  });

});
