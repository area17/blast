<?php

namespace A17\Blast\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use A17\Blast\Traits\Helpers;

class GenerateUIDocs extends Command
{
    use Helpers;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'blast:generate-docs
                                                {--force : Override files without prompting}
                                                {--update-data : Only update the data for the docs. Doesn\'t copy any files}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically generate stories for documenting your Tailwind config';

    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * @var string
     */
    private $vendorPath;

    /**
     * @var mixed
     */
    private $storiesToGenerate;

    /**
     * @var array
     */
    private $config;

    /**
     * @param Filesystem $filesystem
     */
    public function __construct(Filesystem $filesystem)
    {
        parent::__construct();

        $this->config = [];
        $this->storiesToGenerate = config('blast.auto_documentation', []);
        $this->vendorPath = $this->getVendorPath();

        $this->filesystem = $filesystem;
    }

    /*
     * Executes the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $copied = false;
        $force = $this->option('force');
        $updateData = $this->option('update-data');

        if (!$updateData) {
            $copied = $this->copyFiles($force);
        } else {
            $this->info('Updating story data');
        }

        if ($copied || (!$copied && $updateData)) {
            $this->info('Generating stories');
            usleep(500000);
            $this->call('blast:generate-stories');
        }

        return 1;
    }

    /**
     * @return boolean
     */
    private function copyFiles($force = false)
    {
        if (empty($this->storiesToGenerate)) {
            $this->error(
                'No docs defined. Define which docs stories to generate in `config/blast.php` (`auto_documentation`). Aborting.',
            );
            return false;
        }

        $pathname = $this->ask(
            'What do you want to name the documentation section?',
            'UI Documentation',
        );

        $localStoriesPath = base_path('resources/views/stories/' . $pathname);
        $localDataPath = base_path('resources/views/stories/data');

        $packageStoriesPath = $this->vendorPath . '/resources/ui-docs/stories';
        $packageDataPath = $this->vendorPath . '/resources/ui-docs/data';

        if (!$force && $this->filesystem->exists($localStoriesPath)) {
            if (
                $this->confirm(
                    $pathname .
                        ' exists. This will overwrite the existing files. Do you wish to continue?',
                )
            ) {
                $this->info('Overwriting UI Docs stories');
            } else {
                $this->error('Aborting');

                return false;
            }
        }

        $this->filesystem->ensureDirectoryExists($localStoriesPath);
        $this->filesystem->ensureDirectoryExists($localDataPath);

        if (
            is_array($this->storiesToGenerate) &&
            !empty($this->storiesToGenerate)
        ) {
            foreach ($this->storiesToGenerate as $name) {
                $filepath =
                    $this->vendorPath .
                    '/resources/ui-docs/stories/' .
                    $name .
                    '.blade.php';

                if ($this->filesystem->exists($filepath)) {
                    if ($this->filesystem->exists($packageStoriesPath)) {
                        $this->info('Copying stories for `' . $name . '`.');

                        // transitions also require the data file
                        if ($name === 'transition') {
                            $dataFilepath = $packageDataPath . '/ui-docs.php';

                            if ($this->filesystem->exists($dataFilepath)) {
                                $this->filesystem->copy(
                                    $dataFilepath,
                                    $localDataPath . '/ui-docs.php',
                                );
                            }
                        }

                        // copy documentation story
                        $this->filesystem->copy(
                            $filepath,
                            $localStoriesPath . '/' . $name . '.blade.php',
                        );
                    }
                } else {
                    $this->error('`' . $name . '` not recognized. Ignoring.');
                }
            }
        }

        return true;
    }
}
