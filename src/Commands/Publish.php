<?php

namespace A17\Blast\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use A17\Blast\Traits\Helpers;

class Publish extends Command
{
    use Helpers;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'blast:publish
                                        {--install : Force install dependencies}
                                        {--o|output-dir=storybook-static : Directory where to store built files}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Build Static Storybook Instance.';

    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * @param Filesystem $filesystem
     */
    public function __construct(Filesystem $filesystem)
    {
        parent::__construct();

        $this->filesystem = $filesystem;
        $this->storybookServer = config('blast.storybook_server_url');
        $this->vendorPath = $this->getVendorPath();
        $this->storybookStatuses = config('blast.storybook_statuses');
        $this->storybookTheme = config('blast.storybook_theme', false);
        $this->storybookSortOrder = config('blast.storybook_sort_order', []);
        $this->storybookGlobalTypes = config(
            'blast.storybook_global_types',
            [],
        );
    }

    /*
     * Executes the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $npmInstall = $this->option('install');
        $installMessage = $this->getInstallMessage($npmInstall);
        $outputDir = $this->option('output-dir');

        if (Str::startsWith($outputDir, '/')) {
            $outputDir = Str::after($outputDir, '/');
        }

        $progressBar = $this->output->createProgressBar(3);
        $progressBar->setFormat('%current%/%max% [%bar%] %message%');

        $progressBar->setMessage($installMessage);
        $progressBar->start();

        // remove cors check file
        $cors_file_path = $this->vendorPath . '/tmp/_blast';
        if ($this->filesystem->exists($cors_file_path)) {
            $this->filesystem->delete($cors_file_path);
        }

        if ($npmInstall) {
            $this->newLine();
        }

        // install
        $this->installDependencies($npmInstall);

        usleep(250000);

        $this->info('');
        $progressBar->setMessage('Generating Stories...');
        $progressBar->advance();

        $this->call('blast:generate-stories');

        $this->info('');
        $progressBar->setMessage('Starting static Storybook build...');
        $progressBar->advance();

        $process = ['npm', 'run', 'build-storybook'];

        if ($outputDir) {
            $process[] = '--';

            $process = array_merge($process, ['-o', $outputDir]);
        }

        $this->runProcessInBlast($process, true, [
            'STORYBOOK_SERVER_URL' => $this->storybookServer,
            'STORYBOOK_STATUSES' => json_encode($this->storybookStatuses),
            'STORYBOOK_THEME' => json_encode($this->storybookTheme),
            'STORYBOOK_GLOBAL_TYPES' => json_encode(
                $this->storybookGlobalTypes,
            ),
            'LIBSTORYPATH' => $this->vendorPath . '/stories',
            'PROJECTPATH' => base_path(),
            'COMPONENTPATH' => base_path('resources/views/stories'),
            'STORYBOOK_SORT_ORDER' => json_encode($this->storybookSortOrder),
        ]);

        usleep(250000);

        $this->info('');
        $progressBar->setMessage(
            'Copying static build to `/public/' . $outputDir . '`..',
        );
        $progressBar->advance();

        $outputPath = $this->vendorPath . '/' . $outputDir;
        $destPath = public_path($outputDir);

        $this->CopyDirectory($outputPath, $destPath);

        $this->info('');
        $progressBar->setMessage('Publish Complete');
        $progressBar->finish();

        $this->newLine();

        $this->info('View at ' . url($outputDir . '/index.html'));
    }
}
