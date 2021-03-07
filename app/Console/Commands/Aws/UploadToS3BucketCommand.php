<?php

namespace Rowles\Console\Commands\Aws;

use File;
use Storage;
use Illuminate\Console\Command;
use Rowles\Console\Interfaces\BaseProcessorInterface;
use Illuminate\Contracts\Filesystem\FileNotFoundException;

class UploadToS3BucketCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var mixed
     */
    protected $signature = 'aws:s3:upload {type? : thumbnails, previews or videos}
        {--g|gif : Upload thumbnail GIFs instead of JPEGs }';

    /**
     * The console command description.
     *
     * @var mixed
     */
    protected $description = 'Upload objects from local app storage to S3';

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
     * @return bool
     */
    public function handle(BaseProcessorInterface $processor): bool
    {
        if ($this->argument('type') === 'thumbnails') {
            $files = $processor->scanRecursive(
                $processor->thumbnailStorageDestination("", $this->option('gif'))
            );
            $dest = $this->option('gif') ? 'images/gif/' : 'images/jpeg/';
        } elseif ($this->argument('type') === 'previews') {
            $files = $processor->scanRecursive(
                $processor->previewStorageDestination("")
            );
            $dest = 'previews/';
        } elseif ($this->argument('type') === 'videos') {
            $files = $processor->scanRecursive(
                $processor->videoStorageSource()
            );
            $dest = 'videos/';
        } else {
            $this->output->writeln("<fg=red>[error]</> argument 'type' must be either thumbnails, previews, or videos");
            return false;
        }

        $total = count($files);
        $idx = 0;
        foreach ($files['items'] as $file) {
            try {
                $this->output->writeln('uploading ' . $file['name'] .
                    ' to s3://'. config('filesystems.disks.s3.bucket') .'/' . $dest);

                Storage::disk('s3')->put($dest . $file['name'], File::get($file['path']));
            } catch(FileNotFoundException $e) {
                $this->output->error($e->getMessage());
            }

            $idx++;
        }

        $this->output->writeln('<fg=blue>[info]</> ' . $idx . ' of ' . $total . ' files imported');

        return true;
    }
}
