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
                                {--o|output-dir=storybook-static : Directory where to store built files}
                                {--s|static-dir= : Directory where to load static files from relative to project root, comma-separated list}';

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
        $outputDir = $this->option('output-dir');
        $staticDir = $this->option('static-dir');

        if (Str::startsWith($outputDir, '/')) {
            $outputDir = Str::after($outputDir, '/');
        }

        $this->info('Starting static Storybook build..');

        $process = ['npm', 'run', 'build-storybook'];

        if ($outputDir || $staticDir) {
            $process[] = '--';

            if ($outputDir) {
                $process = array_merge($process, ['-o', $outputDir]);
            }

            if ($staticDir) {
                $process = array_merge($process, ['-s', base_path($staticDir)]);
            }
        }

        $this->runProcessInBlast($process, true, [
            'STORYBOOK_SERVER_URL' => $this->storybookServer,
            'STORYBOOK_STATUSES' => json_encode($this->storybookStatuses),
            'STORYBOOK_THEME' => json_encode($this->storybookTheme),
            'LIBSTORYPATH' => base_path($this->vendorPath . '/stories'),
            'PROJECTPATH' => base_path(),
            'COMPONENTPATH' => base_path('resources/views/stories'),
        ]);

        $this->info('Copying static build to `/public/' . $outputDir . '`..');

        $this->info('View at ' . url($outputDir . '/index.html'));

        $outputPath = $this->vendorPath . '/' . $outputDir;
        $destPath = public_path($outputDir);

        $this->CopyDirectory($outputPath, $destPath);
    }
}
