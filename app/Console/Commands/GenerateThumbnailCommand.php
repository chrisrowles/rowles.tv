<?php

namespace Rowles\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Rowles\Console\OutputHandler;
use Rowles\Console\Processors\ThumbnailProcessor;

class GenerateThumbnailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var mixed
     */
    protected $signature = 'vid:thumbnail {name? : Optional filename}
        {--b|bulk : Bulk mode}
        {--g|gif : Generate GIFs}
        {--from= : Generate frame from time}
        {--duration= : Number of seconds to capture for gif thumbnails}';

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
     * @param ThumbnailProcessor $processor
     * @return void
     */
    public function handle(ThumbnailProcessor $processor): void
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
