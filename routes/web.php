<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;


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

Route::group(['middleware' => ['auth', 'verified']], function() {
    Route::get('/', 'VideoController@index')->name('video.index');
    Route::get('/search', 'VideoController@search')->name('video.search');
    Route::get('/watch/{id}', 'VideoController@watch')->name('video.watch');

    Route::group(['prefix' => 'membership'], function() {
        Route::get('/packages', 'Membership\SubscriptionController@index')->name('membership.packages');
    });

    Route::group(['prefix' => 'api'], function() {
        Route::get('/video', 'Api\VideoController@index')->name('api.video.index');
        Route::get('/video/{id}', 'Api\VideoController@get')->name('api.video.get');
        Route::put('/video/{id}', 'Api\VideoController@update')->name('api.video.update');
    });

    Route::group(['prefix' => '4a198aba'], function() {
        Route::get('/dashboard','Admin\DashboardController@index')->name('dashboard');
    });
});

require __DIR__.'/auth.php';
