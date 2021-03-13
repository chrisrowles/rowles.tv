<?php

namespace Rowles\Console\Commands;

use Illuminate\Console\Command;

class ProcessAllTasksForVideosCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vid:process {path?} {--bulk} {--from=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run all processing tasks for videos.';

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
     * @return void
     */
    public function handle() : void
    {
        if ($this->call('vid:import', ['path' => $this->argument('path')]) === 0) {
//            if ($this->call('vid:metadata', [
//                'path' => $this->argument('path'),
//                '--bulk' => $this->option('bulk'),
//                '--is-job' => $this->option('is-job'),
//            ]) === 0) {
//                $this->call('vid:thumbnail', [
//                    'path' => $this->argument('path'),
//                    '--bulk' => $this->option('bulk'),
//                    '--from' => $this->option('from'),
//                    '--is-job' => $this->option('is-job')
//                ]);
//            };

            //  Resolves processor instances from the service container and automatically sets the console for output
            //  and maps options for processing.
             foreach (['metadata.processor', 'thumbnail.processor'] as $processor) {
                 app($processor)->setConsole($this->output)
                     ->mapOptions($this->options())
                     ->execute();
             }
        }
    }
}
