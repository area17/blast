<?php

namespace A17\Blast\Commands;

use Illuminate\Support\Str;
use A17\Blast\Traits\Helpers;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class PublishStorybookConfig extends Command
{
    use Helpers;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'blast:publish-storybook-config';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish Storybook config files to project directory';

    protected Filesystem $filesystem;

    private string $vendorPath;

    public function __construct(Filesystem $filesystem)
    {
        parent::__construct();

        $this->filesystem = $filesystem;
        $this->vendorPath = $this->getVendorPath();
    }

    /*
     * Executes the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // copy blast default configs to .storybook
        $blastConfigPath = $this->vendorPath . '/.storybook';
        $projectConfigPath = base_path('.storybook');
        $copyFiles = true;

        if ($this->filesystem->exists($projectConfigPath)) {
            $copyFiles = $this->confirm(
                'Config already exists in project directory. Overwrite? This cannot be undone.',
                false,
            );
        }

        if (!$copyFiles) {
            $this->error('Aborting');

            return 0;
        }

        $this->filesystem->copyDirectory($blastConfigPath, $projectConfigPath);

        // Update paths in preview.js
        if ($this->filesystem->exists($projectConfigPath . '/preview.js')) {
            $this->filesystem->replaceInFile(
                '../public/main.css',
                '../vendor/area17/blast/public/main.css',
                $projectConfigPath . '/preview.js',
            );
        }

        // Update paths in main.js
        $mainJsPath = $projectConfigPath . '/main.js';

        if ($this->filesystem->exists($mainJsPath)) {
            $this->filesystem->replaceInFile(
                '../stories/**/*.stories.json',
                '../vendor/area17/blast/stories/**/*.stories.json',
                $mainJsPath,
            );

            $mainJsContents = $this->filesystem->get($mainJsPath);
            preg_match('/addons: [ \t]*\[(.*)\]/sU', $mainJsContents, $matches);

            if (filled($matches)) {
                $toReplace = preg_split(
                    '/(\s*,*\s*)*,+(\s*,*\s*)*/',
                    trim($matches[1]),
                );

                $essentials = [
                    'actions',
                    'backgrounds',
                    'controls',
                    'docs',
                    'highlight',
                    'measure',
                    'outline',
                    'toolbars',
                    'viewport',
                ];

                $replaceWith = [];

                foreach ($toReplace as $item) {
                    $prefix = '../vendor/area17/blast/node_modules/';
                    $newPath = Str::of($item)
                        ->between("'", "'")
                        ->start($prefix);

                    if (Str::contains($item, '@storybook/addon-essentials')) {
                        $newEssentials = [];
                        $newPath = $newPath->finish('/dist/');

                        foreach ($essentials as $essential) {
                            $newEssentials[] = $newPath
                                ->finish($essential)
                                ->start("'")
                                ->finish("'")
                                ->toString();
                        }

                        $replaceWith = array_merge(
                            $replaceWith,
                            $newEssentials,
                        );
                    } else {
                        if (Str::contains($item, '@storybook/addon-links')) {
                            $newPath = $newPath->finish('/dist');
                        }

                        $replaceWith[] = $newPath
                            ->start("'")
                            ->finish("'")
                            ->toString();
                    }
                }

                // dd($replaceWith);

                $this->filesystem->replaceInFile(
                    $matches[1],
                    implode(",\n", $replaceWith),
                    $mainJsPath,
                );
            }
        }

        $this->info('Copied files to .storybook in your project directory');
        $this->info(
            'Note that any future changes to the storybook config files in blast will have to be manually applied to the config files in your project.',
        );
    }
}
