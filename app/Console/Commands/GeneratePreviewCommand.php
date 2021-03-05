<?php

namespace Rowles\Console\Commands;

use Illuminate\Console\Command;
use Rowles\Console\Interfaces\PreviewProcessorInterface;
use Rowles\Console\OutputHandler;

class GeneratePreviewCommand extends Command
{
    /** @var string  */
    protected string $identifier = 'previews';

    /**
     * The name and signature of the console command.
     *
     * @var mixed
     */
    protected $signature = 'vid:preview {name? : Optional filename}
        {--b|bulk : Generate previews in bulk mode}
        {--start= : Starting point for preview (default: 10)}
        {--seconds= : Number of seconds to capture for preview (default: 10)}';

    /**
     * The console command description.
     *
     * @var mixed
     */
    protected $description = 'Generate video previews';

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
     * @param PreviewProcessorInterface $processor
     * @return void
     */
    public function handle(PreviewProcessorInterface $processor): void
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
            $this->option('bulk')
        );

        OutputHandler::handle($process, $this->output, $this->identifier);
    }
}
