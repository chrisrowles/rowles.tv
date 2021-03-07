<?php

namespace Rowles\Console\Commands;

use Rowles\Console\Interfaces\TranscodeProcessorInterface;
use Illuminate\Console\Command;
use Rowles\Console\OutputHandler;

class TranscodeVideoCommand extends Command
{
    /** @var string  */
    protected string $identifier = 'transcode';

    /**
     * The name and signature of the console command.
     *
     * @var mixed
     */
    protected $signature = 'vid:transcode {name? : Video filename}
        {--b|bulk : Transcode videos in bulk mode}
        {--c|clip : Enable clip}
        {--from= : Clip from seconds}
        {--duration= : Clip duration}
        {--r|resize : Enable resize}
        {--width= : Resize width}
        {--height= : Resize height}
        {--e|ext= : File format}
        {--bitrate= : Kilo bitrate}
        {--audio-bitrate= : Audio bitrate}
        {--audio-channels= : Audio channels};
        {--constant-rate-factor= : Constant rate factor}';

    /**
     * The console command description.
     *
     * @var mixed
     */
    protected $description = 'Transcode videos';

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
     * @param TranscodeProcessorInterface $processor
     * @return void
     */
    public function handle(TranscodeProcessorInterface $processor): void
    {
        try {
            $processor->setConsole($this->output)->mapOptions($this->options());

            $process = $processor->execute($this->argument('name'), $this->option('ext'));

            OutputHandler::handle($process, $this->output, $this->identifier);
        } catch (\Exception $e) {
            $this->output->error($e->getMessage());
        }
    }
}
