<?php

namespace Rowles\Console\Commands;

use Rowles\Models\Video;
use Illuminate\Console\Command;
use Rowles\Console\Processors\BaseProcessorInterface;

class ImportVideosCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vid:import {path? : Optional path to file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import videos';

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
     * @param BaseProcessorInterface $processor
     * @return void
     */
    public function handle(BaseProcessorInterface $processor): void
    {
        if (!$this->argument('path')) {
            $scan = $processor->getVideosFromStorage();
        } else {
            $scan = $processor->scanRecursive($this->argument('path'));
        }

        $this->output->writeln('<fg=blue>[info]</> ' . $scan['total'] . ' videos to import');

        $idx = 0;
        $this->processRecursive($processor, $scan, $idx);

        if ($idx === 0) {
            $this->output->writeln('<fg=yellow>[info]</> No videos imported.');
        } else {
            $this->output->writeln('<fg=blue>[info]</> ' . $idx . '/' . $scan['total'] . ' videos imported.');
        }
    }

    /**
     * @param $scan
     * @param BaseProcessorInterface $processor
     * @param int $idx
     */
    private function processRecursive(BaseProcessorInterface $processor, array $scan, int &$idx)
    {
        foreach ($scan['items'] as $file) {
            if ($file['type'] === 'folder') {
                $this->processRecursive($processor, $file['items'], $idx);
            } else {
                $this->process($processor, $file, $idx);
            }
        }
    }

    /**
     * @param BaseProcessorInterface $processor
     * @param array $file
     * @param int $idx
     */
    private function process(BaseProcessorInterface $processor, array $file, int &$idx): void
    {
        if ($file['type'] === 'file' && $processor->extAllowed($file['name'])) {
            if (!Video::where('filepath', $file['path'])->exists()) {
                $video = new Video;
                $video->filepath = $file['path'];
                $video->filename = $file['name'];
                $video->title = $file['name'];

                if (!$video->save()) {
                    $this->output->writeln('<fg=red>[error]</> failed to save record for ' . $file['path']);

                } else {
                    $this->output->writeln('<fg=green>[success]</> [' . $file['path'] .'] imported.');
                    ++$idx;
                }
            } else {
                $this->output->writeln('  - ' . $file['path'] . ' already imported');
            }
        }
    }
}
