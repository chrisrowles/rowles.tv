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
    Route::post('/billing', 'BillingController@post')->name('billing');
    Route::get('/billing/confirm', 'BillingController@confirm')->name('billing.confirm');
    Route::get('/billing/portal', 'BillingController@portal')->name('billing.portal');

    Route::middleware('subscribed')->group(function() {
        Route::get('/', 'VideoController@index')->name('video.index');
        Route::get('/search', 'VideoController@search')->name('video.search');
        Route::get('/watch/{id}', 'VideoController@watch')->name('video.watch');

        Route::prefix('account-management')->group(function() {
            Route::get('/', 'AccountController@index')->name('account.index');
        });

        Route::prefix('admin')->middleware('administrator')->group(function() {
            Route::prefix('video-processing')->group(function() {
                Route::get('/dashboard', 'Admin\ProcessingController@index')->name('admin.processing');
                Route::get('/run/{namespace}/{command}', 'Admin\ProcessingController@run')->name('admin.processing.run');
            });

            Route::prefix('video-management')->group(function() {
                Route::get('/','Admin\VideoController@index')->name('admin.video');
                Route::get('/{id}','Admin\VideoController@get')->name('admin.video.get');
                Route::put('/{id}', 'Admin\VideoController@update')->name('admin.video.update');
            });

            Route::prefix('subscription-management')->group(function() {
                Route::get('/', 'Admin\SubscriptionController@index')->name('admin.subscription');
                Route::put('/{plan}', 'Admin\SubscriptionController@update')->name('admin.subscription.update');
            });
        });
    });
});

require __DIR__.'/auth.php';
