<?php

namespace Rowles\Providers;

use Auth;
use Blade;
use Illuminate\Support\ServiceProvider;
use Rowles\ArtisanMapper;
use Rowles\DefaultPaymentProvider;
use Rowles\MapperInterface;
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

        $this->app->singleton(MapperInterface::class, function() {
            return new ArtisanMapper();
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
