<?php

namespace Rowles\Console\Commands;

use Illuminate\Console\Command;
use Rowles\Console\Interfaces\MetadataProcessorInterface;
use Rowles\Console\OutputHandler;

class ExtractMetadataCommand extends Command
{
    /** @var string  */
    protected string $identifier = 'metadata';

    /**
     * The name and signature of the console command.
     *
     * @var mixed
     */
    protected $signature = 'vid:metadata {name? : Optional filename}
        {--b|bulk : Extract metadata in bulk mode}';

    /**
     * The console command description.
     *
     * @var mixed
     */
    protected $description = 'Extract video metadata';

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
     * @param MetadataProcessorInterface $processor
     * @return void
     */
    public function handle(MetadataProcessorInterface $processor): void
    {
        $processor->setConsole($this->output);

        $process = $processor->execute(
            $this->argument('name') ?? "",
            $this->option('bulk')
        );

        OutputHandler::handle($process, $this->output, $this->identifier);
    }
}
