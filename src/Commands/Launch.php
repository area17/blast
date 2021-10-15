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
    }

    /*
     * Executes the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $progressBar = $this->output->createProgressBar(5);
        $progressBar->setFormat('%current%/%max% [%bar%] %message%');

        $npmInstall = $this->option('install');
        $noInstall = $this->option('noInstall');
        $depsInstalled = $this->filesystem->exists(
            $this->vendorPath . '/node_modules/@storybook',
        );

        $progressBar->setMessage(
            ($npmInstall || (!$npmInstall && !$depsInstalled)
                ? 'Installing'
                : 'Reusing') . " npm dependencies...\n\n",
        );

        $progressBar->start();

        if ($noInstall) {
            $this->info(
                'The `--noInstall` option has been removed and npm dependencies are now installed automatically. Run `php artisan blast:launch` without options to skip installing dependencies and use the `--install` option to force install dependencies',
            );

            sleep(5);
        }

        // npm install
        if ($npmInstall || (!$npmInstall && !$depsInstalled)) {
            $this->runProcessInBlast([
                'npm',
                'ci',
                '--production',
                '--ignore-scripts',
            ]);
        } else {
            sleep(1);
        }

        // generate stories
        $noGenerate = $this->option('noGenerate');

        if (!$noGenerate) {
            $this->info('');
            $progressBar->setMessage('Generating Stories...');
            $progressBar->advance();

            $this->call('blast:generate-stories');
        } else {
            $progressBar->advance();
            sleep(1);
        }

        // publish FE assets
        $this->info('');
        $progressBar->setMessage('Publishing FE assets.');
        $this->call('vendor:publish', ['--tag' => 'blast-assets']);

        // init storybook and watch stories
        $this->info('');
        $progressBar->setMessage(
            'Setup Complete. Booting Storybook and watching stories.',
        );
        $progressBar->finish();

        sleep(1);

        $this->runProcessInBlast(['npm', 'run', 'storybook'], true, [
            'STORYBOOK_SERVER_URL' => $this->storybookServer,
            'STORYBOOK_STATIC_PATH' => public_path(),
            'STORYBOOK_STATUSES' => json_encode($this->storybookStatuses),
            'STORYBOOK_THEME' => json_encode($this->storybookTheme),
            'LIBSTORYPATH' => $this->vendorPath . '/stories',
            'PROJECTPATH' => base_path(),
            'COMPONENTPATH' => base_path('resources/views/stories'),
        ]);
    }
}
