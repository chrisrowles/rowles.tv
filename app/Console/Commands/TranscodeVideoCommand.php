<?php

namespace Rowles\Console\Commands;

use Rowles\Console\Interfaces\TranscodeProcessorInterface;
use Rowles\Console\OutputFormatter;
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
    protected $signature = 'vid:transcode {name : Video filename}
        {--f|format= : The selected format}
        {--b|bitrate= : Kilo bitrate (default: 1000)}
        {--ab|audio-bitrate= : Audio bitrate (default: 256)}
        {--ac|audio-channels= : Audio channels (default: 2)};
        {--crf|constant-rate-factor= : Constant rate factor (default: 20)}';

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
        $processor->setConsole($this->output);

        if ($this->option('bitrate')) {
            $processor->setKiloBitrate($this->option('bitrate'));
        }

        if ($this->option('audio-bitrate')) {
            $processor->setAudioKiloBitrate($this->option('audio-bitrate'));
        }

        if ($this->option('audio-channels')) {
            $processor->setAudioChannels($this->option('audio-channels'));
        }

        if ($this->option('constant-rate-factor')) {
            $processor->setConstantRateFactor($this->option('constant-rate-factor'));
        }

        $process = $processor->execute($this->argument('name'), $this->option('format'));

        OutputHandler::handle($process, $this->output, $this->identifier);
    }
}
