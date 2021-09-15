<?php

namespace A17\Blast\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class Demo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'blast:demo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Build example component and stories';

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
        $this->storyViewsPath = base_path('resources/views/stories');
        $this->vendorPath = config('blast.vendor_path');
    }

    /*
     * Executes the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // copy demo files
        $this->CopyComponentFiles();
        $this->CopyStoryFiles();

        // run generate stories
        $demoComponentPath =
            $this->storyViewsPath . '/blast-demo/button.blade.php';

        $this->call('blast:generate-stories', [
            'component' => $demoComponentPath,
        ]);
    }

    /**
     * @return void
     */
    private function CopyComponentFiles()
    {
        $localComponentsPath = base_path(
            'resources/views/components/blast-demo',
        );
        $packageComponentsPath =
            base_path($this->vendorPath) . '/demo/components';

        $this->filesystem->ensureDirectoryExists($localComponentsPath);

        $this->filesystem->cleanDirectory($localComponentsPath);

        if ($this->filesystem->exists($packageComponentsPath)) {
            $this->filesystem->copyDirectory(
                $packageComponentsPath,
                $localComponentsPath,
            );
        }
    }

    /**
     * @return void
     */
    private function CopyStoryFiles()
    {
        $localComponentsPath = base_path('resources/views/stories/blast-demo');
        $packageComponentsPath = base_path($this->vendorPath) . '/demo/stories';

        $this->filesystem->ensureDirectoryExists($localComponentsPath);

        $this->filesystem->cleanDirectory($localComponentsPath);

        if ($this->filesystem->exists($packageComponentsPath)) {
            $this->filesystem->copyDirectory(
                $packageComponentsPath,
                $localComponentsPath,
            );
        }
    }
}
