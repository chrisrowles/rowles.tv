<?php

namespace Rowles\Console\Commands;

use Illuminate\Console\Command;
use Rowles\Console\Interfaces\ThumbnailProcessorInterface;
use Rowles\Console\OutputHandler;

class GenerateThumbnailCommand extends Command
{
    /** @var string  */
    protected string $identifier = 'thumbnails';

    /**
     * The name and signature of the console command.
     *
     * @var mixed
     */
    protected $signature = 'vid:thumbnail {name? : Optional filename}
        {--g|gif : Render thumbnails in GIF format}
        {--b|bulk : Generate thumbnails in bulk mode}
        {--start= : Starting point for thumbnails}
        {--seconds= : Number of seconds to capture for gif thumbnails}';

    /**
     * The console command description.
     *
     * @var mixed
     */
    protected $description = 'Generate video thumbnails';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @param ThumbnailProcessorInterface $processor
     * @return void
     */
    public function handle(ThumbnailProcessorInterface $processor): void
    {
        $processor->setConsole($this->output);

        if ($this->option('start')) {
            $processor->setStart($this->option('start'));
        }

        if ($this->option('seconds')) {
            $processor->setSeconds($this->option('seconds'));
        }

        $process = $processor->execute(
            $this->argument('name') ?? "",
            $this->option('gif'),
            $this->option('bulk')
        );

        OutputHandler::handle($process, $this->output, $this->identifier);
    }
}
