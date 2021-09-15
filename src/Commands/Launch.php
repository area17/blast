<?php

namespace A17\Blast\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Process\Process;

class Launch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'blast:launch {--noInstall} {--noGenerate}';

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
        $this->vendorPath = config('blast.vendor_path');
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

        $npmInstall = !$this->option('noInstall');

        $progressBar->setMessage(
            ($npmInstall ? 'Installing' : 'Reusing') .
                " npm dependencies...\n\n",
        );

        $progressBar->start();

        // npm install
        if ($npmInstall) {
            if (
                $this->filesystem->exists(
                    $this->vendorPath . '/node_modules/@storybook',
                )
            ) {
                if (
                    $this->confirm(
                        'It looks like Storybook is already installed. Install anyway? Tip: add --noInstall to skip this step in the future',
                        true,
                    )
                ) {
                    $this->runProcessInBlast(['npm', 'ci']);
                }
            } else {
                $this->runProcessInBlast(['npm', 'ci']);
            }
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
            'STORYBOOK_STATUSES' => json_encode($this->storybookStatuses),
            'STORYBOOK_THEME' => json_encode($this->storybookTheme),
            'LIBSTORYPATH' => base_path($this->vendorPath . '/stories'),
            'PROJECTPATH' => base_path(),
            'COMPONENTPATH' => base_path('resources/views/stories'),
        ]);
    }

    /**
     * @return void
     */
    private function runProcessInBlast(
        array $command,
        $disableTimeout = false,
        $envVars = null,
    ) {
        $process = new Process(
            $command,
            base_path($this->vendorPath),
            $envVars,
        );
        $process->setTty(Process::isTtySupported());

        if ($disableTimeout) {
            $process->setTimeout(null);
        } else {
            $process->setTimeout(config('blast.build_timeout', 300));
        }

        $process->mustRun();
    }
}
