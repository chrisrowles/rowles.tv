<?php

namespace Rowles\Providers;

use Auth;
use Blade;
use Illuminate\Support\ServiceProvider;
use Rowles\DefaultPaymentProvider;
use Rowles\Models\User;
use Rowles\PaymentProviderInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(PaymentProviderInterface::class, function() {
            return new DefaultPaymentProvider(config('stripe.secret_key'));
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::if('admin', function() {
            return Auth::check() && Auth::user()->role === User::ADMINISTRATOR;
        });
    }
}
