<?php

namespace Rowles\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Rowles\Console\Interfaces\TranscodeProcessorInterface;

class TranscodeVideo implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var string  */
    protected string $source;

    /** @var string  */
    protected string $format;

    /**
     * Create a new job instance.
     *
     * @param string $source
     * @param string $format
     */
    public function __construct(string $source, string $format)
    {
        $this->source = $source;
        $this->format = $format;
    }

    /**
     * Execute the job.
     *
     * @param TranscodeProcessorInterface $processor
     * @return void
     */
    public function handle(TranscodeProcessorInterface $processor)
    {
        $processor->execute($this->source, $this->format);
    }
}
