<?php

namespace Rowles\Console\Commands;

use Illuminate\Console\Command;
use Rowles\Console\Interfaces\BaseProcessorInterface;
use Rowles\Console\OutputHandler;
use Rowles\Models\Video;

class ImportVideosCommand extends Command
{
    /** @var string  */
    protected string $identifier = 'imports';

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

    /** @var array  */
    protected array $process = [];

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->process = [
            'status' => 'success',
            'errors' => [$this->identifier => 0],
        ];

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
            $items = $processor->getVideosFromStorage();
        } else {
            $items = $processor->scanRecursive($this->argument('path'));
        }

        foreach ($items as $item) {
            if ($item['type'] === 'folder') {
                foreach ($item['items'] as $file) {
                    $this->process($processor, $file);
                }
            } else {
                $this->process($processor, $item);
            }
        }

        if ($this->process['errors'][$this->identifier] > 0) {
            $this->process['status'] = 'error';
        }

        OutputHandler::handle($this->process, $this->output, $this->identifier);
    }

    private function process(BaseProcessorInterface $processor, $file): void
    {
        if ($file['type'] === 'file' && $processor->extAllowed($file['name'])) {
            if (!Video::where('filename', $file['path'])->exists()) {
                $video = new Video;
                $video->filepath = $file['path'];
                $video->filename = $file['name'];

                if (!$video->save()) {
                    ++$this->process['errors'][$this->identifier];
                }
            }
        }
    }
}
