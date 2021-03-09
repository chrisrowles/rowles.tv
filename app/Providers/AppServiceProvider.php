<?php

namespace Rowles\Providers;

use Illuminate\Support\ServiceProvider;
use Rowles\DefaultPaymentProvider;
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
     *N
     * @return void
     */
    public function boot()
    {
        //
    }
}
