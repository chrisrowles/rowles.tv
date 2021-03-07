<?php

namespace Rowles\Providers;

use Illuminate\Support\ServiceProvider;

use Rowles\Console\Processors\BaseProcessor;
use Rowles\Console\Interfaces\BaseProcessorInterface;

class ProcessorServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(BaseProcessorInterface::class, function() {
            return new BaseProcessor();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
