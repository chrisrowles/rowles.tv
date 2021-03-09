<?php

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

Route::middleware(['auth', 'verified'])->group(function() {
    Route::get('/subscribe', 'SubscribeController@index')->name('subscribe');
    Route::post('/billing', 'BillingController@index')->name('billing');
    Route::get('/billing/confirm', 'BillingController@confirm')->name('billing.confirm');
    Route::get('/billing/portal', 'BillingController@portal')->name('billing.portal');

    Route::middleware('subscribed')->group(function() {
        Route::get('/', 'VideoController@index')->name('video.index');
        Route::get('/search', 'VideoController@search')->name('video.search');
        Route::get('/watch/{id}', 'VideoController@watch')->name('video.watch');

        Route::prefix('account')->group(function() {
            Route::get('/', 'AccountController@index')->name('account.index');
        });

        Route::prefix('admin')->middleware('administrator')->group(function() {
            Route::prefix('api')->group(function() {
                Route::get('/video', 'Api\VideoController@index')->name('api.video.index');
                Route::get('/video/{id}', 'Api\VideoController@get')->name('api.video.get');
                Route::put('/video/{id}', 'Api\VideoController@update')->name('api.video.update');
            });

            Route::get('/dashboard','Admin\DashboardController@index')->name('admin.dashboard');
        });
    });
});

require __DIR__.'/auth.php';
