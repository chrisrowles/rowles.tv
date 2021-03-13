<?php

namespace Rowles\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Rowles\Console\OutputHandler;
use Rowles\Console\Processors\MetadataProcessor;
use Rowles\Jobs\ExtractMetadata;

class ExtractMetadataCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var mixed
     */
    protected $signature = 'vid:metadata {path? : Optional filepath}
        {--b|bulk : Extract metadata in bulk mode}
        {--is-job : Process as background job}';

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

            if ($this->option('is-job')) {
                ExtractMetadata::dispatch($this->argument('path'), $this->options());
                $this->output->info('Metadata extraction job for ' . $this->argument('path') .' dispatched.');
            } else {
                $processor->setConsole($this->output)->mapOptions($this->options());
                $process = $processor->execute($this->argument('path'));

                OutputHandler::handle($process, $this->output, $processor->identifier);
            }
        } catch (Exception $e) {
            $this->output->error($e->getMessage());
        }
    }
}
