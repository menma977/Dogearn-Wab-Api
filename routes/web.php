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

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Authorization');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE');

Route::get('/', function () {
  return view('welcome');
});

Route::get('/success', function () {
  return view('welcomeNewMember');
});

Route::get('/confirmation/{email}/{password}', 'UserController@indexConfirmation')->name('indexConfirmation');

Route::get('/ref/{email}', 'UserController@indexRef')->name('indexRef');

Auth::routes(['register' => false]);

Route::middleware('auth')->group(static function () {
  Route::get('/home', 'HomeController@index')->name('home');
  Route::post('/find', 'HomeController@find')->name('find');

  Route::get('/total/user/view', 'HomeController@totalUserView')->name('totalUserView');
  Route::get('/online/user/view', 'HomeController@onlineUserView')->name('onlineUserView');
  Route::get('/new/user/view/{dateFrom}/{dateNow}', 'HomeController@newUserView')->name('newUserView');
  Route::get('/total/upgrade/view/{dateFrom}/{dateNow}', 'HomeController@totalUpgradeView')->name('totalUpgradeView');
  Route::get('/new/user/not/verified/view', 'HomeController@newUserNotVerifiedView')->name('newUserNotVerifiedView');

  Route::get('/total/user', 'HomeController@totalUser')->name('totalUser');
  Route::get('/online/user', 'HomeController@onlineUser')->name('onlineUser');
  Route::get('/new/user/{dateFrom}/{dateNow}', 'HomeController@newUser')->name('newUser');
  Route::get('/total/upgrade/{dateFrom}/{dateNow}', 'HomeController@totalUpgrade')->name('totalUpgrade');
  Route::get('/user/not/verified', 'HomeController@newUserNotVerified')->name('newUserNotVerified');

  Route::group(['prefix' => 'user', 'as' => 'user.'], static function () {
    Route::get('/', 'UserController@index')->name('index');
    Route::get('/indexDataDynamic', 'UserController@indexDataDynamic')->name('indexDataDynamic');
    Route::get('/suspend/{id}/{status}', 'UserController@suspend')->name('suspend');
    Route::get('/activate/{id}', 'UserController@activate')->name('activate');
    Route::get('/show/{id}', 'UserController@show')->name('show');
    Route::post('/update/password/{id}', 'UserController@updatePassword')->name('updatePassword');
    Route::post('/update/secondary/password/{id}', 'UserController@updateSecondaryPassword')->name('updateSecondaryPassword');
    Route::post('/update/phone/{id}', 'UserController@updatePhone')->name('updatePhone');
    Route::get('/lot/{id}', 'UserController@lotList')->name('lotList');
    Route::get('/pin/{id}', 'UserController@pinList')->name('pinList');
    Route::get('/delete/session/{id}', 'UserController@logoutSession')->name('logoutSession');
  });

  Route::group(['prefix' => 'pin', 'as' => 'pin.'], static function () {
    Route::get('/', 'PinLedgerController@index')->name('index');
    Route::post('/store', 'PinLedgerController@store')->name('store');
  });

  Route::group(['prefix' => 'binary', 'as' => 'binary.'], static function () {
    Route::get('/', 'BinaryController@index')->name('index');
    Route::get('/find/{id}', 'BinaryController@show')->name('show');
  });

  Route::group(['prefix' => 'grade', 'as' => 'grade.'], static function () {
    Route::get('/', 'GradeController@index')->name('index');
    Route::post('/store', 'GradeController@store')->name('store');
    Route::post('/update/{id}', 'GradeController@update')->name('update');
    Route::get('/delete/{id}', 'GradeController@destroy')->name('delete');
  });

  Route::group(['prefix' => 'level', 'as' => 'level.'], static function () {
    Route::get('/', 'LevelController@index')->name('index');
    Route::post('/store', 'LevelController@store')->name('store');
    Route::post('/update/{id}', 'LevelController@update')->name('update');
    Route::get('/delete/{id}', 'LevelController@destroy')->name('delete');
  });

  Route::group(['prefix' => 'setting', 'as' => 'setting.'], static function () {
    Route::get('/', 'SettingController@index')->name('index');
    Route::post('/update/wallet/it', 'SettingController@updateIt')->name('updateIt');
    Route::post('/update/fee', 'SettingController@fee')->name('fee');
    Route::post('/update/fee/admin', 'SettingController@adminFee')->name('adminFee');
    Route::post('/update/app', 'SettingController@app')->name('app');
    Route::get('/shot/down/{status}', 'SettingController@shotDown')->name('shotDown');
    Route::post('/edit/lot', 'SettingController@editLot')->name('editLot');
    Route::post('/save/wallet', 'SettingController@saveWallet')->name('saveWallet');
    Route::post('/edit/wallet/{id}', 'SettingController@editWallet')->name('editWallet');
    Route::get('/delete/wallet/{id}', 'SettingController@deleteWallet')->name('deleteWallet');
  });
});
