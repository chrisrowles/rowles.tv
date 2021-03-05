<?php

namespace Rowles\Providers;

use Illuminate\Support\ServiceProvider;

use Rowles\Console\Processors\BaseProcessor;
use Rowles\Console\Processors\MetadataProcessor;
use Rowles\Console\Processors\PreviewProcessor;
use Rowles\Console\Processors\ThumbnailProcessor;
use Rowles\Console\Processors\TranscodeProcessor;

use Rowles\Console\Interfaces\BaseProcessorInterface;
use Rowles\Console\Interfaces\PreviewProcessorInterface;
use Rowles\Console\Interfaces\MetadataProcessorInterface;
use Rowles\Console\Interfaces\ThumbnailProcessorInterface;
use Rowles\Console\Interfaces\TranscodeProcessorInterface;

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

        $this->app->bind(TranscodeProcessorInterface::class, function() {
            return new TranscodeProcessor();
        });

        $this->app->bind(MetadataProcessorInterface::class, function() {
            return new MetadataProcessor();
        });

        $this->app->bind(ThumbnailProcessorInterface::class, function () {
            return new ThumbnailProcessor();
        });

        $this->app->bind(PreviewProcessorInterface::class, function () {
            return new PreviewProcessor();
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
