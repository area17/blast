<?php

namespace A17\Blast\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use A17\Blast\Traits\Helpers;

class Launch extends Command
{
    use Helpers;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'blast:launch
                                        {--install : Force install dependencies}
                                        {--noInstall : Deprecated. Launch Blast without installing dependencies}
                                        {--noGenerate : Skip auto-generating stories based on existing components}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Init the Blast Storybook instance.';

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
        $noGenerate = $this->option('noGenerate');
        $npmInstall = $this->option('install');
        $noInstall = $this->option('noInstall');
        $installMessage = $this->getInstallMessage($npmInstall);

        // init progress bar
        $progressBar = $this->output->createProgressBar(2);
        $progressBar->setFormat('%current%/%max% [%bar%] %message%');

        // Install step
        $progressBar->setMessage($installMessage);
        $progressBar->start();

        if ($npmInstall) {
            $this->newLine();
        }

        if ($noInstall) {
            $this->info(
                'The `--noInstall` option has been removed and npm dependencies are now installed automatically. Run `php artisan blast:launch` without options to skip installing dependencies and use the `--install` option to force install dependencies',
            );

            sleep(5);
        }

        // install
        $this->installDependencies($npmInstall);

        usleep(250000);

        // generate stories
        if (!$noGenerate) {
            $this->info('');
            $progressBar->setMessage('Generating Stories...');
            $progressBar->advance();

            $this->call('blast:generate-stories');
        } else {
            $this->info('');
            $progressBar->setMessage('Skipping Story Generation...');
            $progressBar->advance();
        }

        usleep(250000);

        // init storybook and watch stories
        $this->info('');
        $progressBar->setMessage(
            'Setup Complete. Booting Storybook and watching stories.',
        );
        $progressBar->finish();

        $this->runProcessInBlast(['npm', 'run', 'storybook'], true, [
            'STORYBOOK_SERVER_URL' => $this->storybookServer,
            'STORYBOOK_STATIC_PATH' => public_path(),
            'STORYBOOK_STATUSES' => json_encode($this->storybookStatuses),
            'STORYBOOK_THEME' => json_encode($this->storybookTheme),
            'STORYBOOK_GLOBAL_TYPES' => json_encode(
                $this->storybookGlobalTypes,
            ),
            'LIBSTORYPATH' => $this->vendorPath . '/stories',
            'PROJECTPATH' => base_path(),
            'COMPONENTPATH' => base_path('resources/views/stories'),
        ]);
    }
}
