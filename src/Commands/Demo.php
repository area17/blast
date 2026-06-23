<?php

namespace A17\Blast\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use A17\Blast\Traits\Helpers;

class Demo extends Command
{
    use Helpers;

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

    protected Filesystem $filesystem;

    private string $vendorPath;

    private string $storyViewsPath;

    public function __construct(Filesystem $filesystem)
    {
        parent::__construct();

        $this->filesystem = $filesystem;
        $this->storyViewsPath = base_path('resources/views/stories');
        $this->vendorPath = $this->getVendorPath();
    }

    /*
     * Executes the console command.
     */
    public function handle(): void
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

    private function CopyComponentFiles(): void
    {
        $localComponentsPath = base_path(
            'resources/views/components/blast-demo',
        );
        $packageComponentsPath = $this->vendorPath . '/demo/components';

        $this->filesystem->ensureDirectoryExists($localComponentsPath);

        $this->filesystem->cleanDirectory($localComponentsPath);

        if ($this->filesystem->exists($packageComponentsPath)) {
            $this->filesystem->copyDirectory(
                $packageComponentsPath,
                $localComponentsPath,
            );
        }
    }

    private function CopyStoryFiles(): void
    {
        $localComponentsPath = base_path('resources/views/stories/blast-demo');
        $packageComponentsPath = $this->vendorPath . '/demo/stories';

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
