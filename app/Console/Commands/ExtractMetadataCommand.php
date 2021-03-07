<?php

namespace Rowles\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Rowles\Console\OutputHandler;
use Rowles\Console\Processors\MetadataProcessor;

class ExtractMetadataCommand extends Command
{
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
     * @param MetadataProcessor $processor
     * @return void
     */
    public function handle(MetadataProcessor $processor): void
    {
        try {
            $processor->setConsole($this->output)->mapOptions($this->options());

            $process = $processor->execute($this->argument('name'));

            OutputHandler::handle($process, $this->output, $processor->identifier);
        } catch (Exception $e) {
            $this->output->error($e->getMessage());
        }
    }
}
