<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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
  return view('welcome');
});

Route::get('/confirmation/{email}/{password}', 'UserController@index')->name('indexConfirmation');

Auth::routes(['register' => false]);

Route::middleware('auth')->group(static function () {
  Route::get('/home', 'HomeController@index')->name('home');

  Route::group(['prefix' => 'pin', 'as' => 'pin.'], static function () {
    Route::get('/', 'PinLedgerController@index')->name('index');
    Route::post('/store', 'PinLedgerController@store')->name('store');
  });

  Route::group(['prefix' => 'binary', 'as' => 'binary.'], static function () {
    Route::get('/', 'BinaryController@index')->name('index');
    Route::get('/find/{id}', 'BinaryController@show')->name('show');
  });
});
