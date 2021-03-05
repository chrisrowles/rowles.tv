<?php

namespace Rowles\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Rowles\Console\Interfaces\ThumbnailProcessorInterface;

class GenerateThumbnail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var string  */
    protected string $source;

    /** @var int  */
    protected int $start;

    /** @var int  */
    protected int $seconds;

    /** @var bool */
    protected bool $isGif;

    /** @var bool  */
    protected bool $bulkMode;

    /**
     * Create a new job instance.
     *
     * @param string $source
     * @param bool $isGif
     * @param int $start
     * @param int $seconds
     * @param bool $bulkMode
     */
    public function __construct(string $source, bool $isGif = false, int $start = 10, int $seconds = 10, bool $bulkMode = false)
    {
        $this->source = $source;
        $this->isGif = $isGif;
        $this->start = $start;
        $this->seconds = $seconds;
        $this->bulkMode = $bulkMode;
    }

    /**
     * Execute the job.
     *
     * @param ThumbnailProcessorInterface $processor
     * @return void
     */
    public function handle(ThumbnailProcessorInterface $processor)
    {
        $processor->setStart($this->start)
            ->setSeconds($this->seconds)
            ->execute($this->source, $this->isGif, $this->bulkMode);
    }
}
