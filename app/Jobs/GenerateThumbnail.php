<?php

namespace Rowles\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Rowles\Console\Processors\ThumbnailProcessor;

class GenerateThumbnail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var mixed  */
    protected $source;

    /** @var array  */
    protected array $options;

    /**
     * Create a new job instance.
     *
     * @param mixed $source
     * @param array $options
     */
    public function __construct($source = null, array $options = [])
    {
        $this->source = $source;
        $this->options = $options;
    }

    /**
     * Execute the job.
     *
     * @param ThumbnailProcessor $processor
     * @return void
     * @throws \Exception
     */
    public function handle(ThumbnailProcessor $processor)
    {
        $processor->mapOptions($this->options)->execute($this->source);
    }
}
