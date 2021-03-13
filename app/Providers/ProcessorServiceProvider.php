<?php

namespace Rowles\Providers;

use Illuminate\Support\ServiceProvider;

use Rowles\Console\Processors\BaseProcessor;
use Rowles\Console\Processors\BaseProcessorInterface;
use Rowles\Console\Processors\MetadataProcessor;
use Rowles\Console\Processors\ThumbnailProcessor;
use Rowles\Console\Processors\TranscodeProcessor;

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

        $this->app->bind('metadata.processor', function() {
            return new MetadataProcessor();
        });

        $this->app->bind('thumbnail.processor', function() {
            return new ThumbnailProcessor();
        });

        $this->app->bind('transcode.processor', function() {
            return new TranscodeProcessor();
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
