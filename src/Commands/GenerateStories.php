<?php

namespace A17\Blast\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use A17\Blast\DataStore;
use A17\Blast\Traits\Helpers;

class GenerateStories extends Command
{
    use Helpers;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'blast:generate-stories {component?} {--watch} {--watchEvent=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically generate stories.json based on example component directory';

    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * @var DataStore
     */
    protected $dataStore;

    /**
     * @param Filesystem $filesystem
     */
    public function __construct(Filesystem $filesystem, DataStore $dataStore)
    {
        parent::__construct();

        $this->dataStore = $dataStore;
        $this->filesystem = $filesystem;
        $this->storyViewsPath = base_path('resources/views/stories');
        $this->vendorPath = $this->getVendorPath();
        $this->packageStoriesPath = $this->vendorPath . '/stories';
    }

    /*
     * Executes the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $component = $this->argument('component');

        $this->filesystem->ensureDirectoryExists($this->packageStoriesPath);
        $this->filesystem->ensureDirectoryExists($this->storyViewsPath);

        if ($component) {
            $this->handleSingleComponent($component);
        } else {
            $this->handleAllComponents();
        }
    }

    /**
     * @return void
     */
    private function handleSingleComponent($component = null)
    {
        $watchEvent = $this->option('watchEvent');
        $storyPathSlash = $this->storyViewsPath . '/';
        $componentPath = str_replace($storyPathSlash, '', $component);

        // remove filename from path
        $fullFilename = Str::afterLast($component, '/');
        $dirname = Str::beforeLast($component, '/');

        $filename = str_replace('.blade.php', '', $fullFilename);
        // if the view is in the folder root, add it to a generic 'components' directory
        if ($dirname === $this->storyViewsPath) {
            $dirname .= '/' . $filename;
        }

        // get relative path
        $relativePath = str_replace($storyPathSlash, '', $dirname);
        $storyPath =
            $this->packageStoriesPath . '/' . $relativePath . '.stories.json';

        // check if story exists
        if ($this->filesystem->exists($storyPath)) {
            $parsedStory = json_decode(
                $this->filesystem->get($storyPath),
                true,
            );

            // find existing story
            $key = false;
            foreach ($parsedStory['stories'] as $storyKey => $story) {
                $storyId = str_replace(
                    [$storyPathSlash, '.blade.php'],
                    '',
                    $component,
                );

                if ($story['parameters']['server']['id'] == $storyId) {
                    $key = $storyKey;
                }
            }

            // if we're deleting a file then remove it from the array
            if ($watchEvent === 'unlink') {
                unset($parsedStory['stories'][$key]);
            } else {
                $storyData = [
                    'name' => $filename,
                    'path' => $componentPath,
                    'options' => $this->getStoryOptions($component),
                ];
                $updatedChildData = $this->buildChildTemplate($storyData);

                // if story exists for this variation of component
                if ($key !== false) {
                    // replace with updated data
                    $parsedStory['stories'][$key] = $updatedChildData;
                } else {
                    $parsedStory['stories'][] = $updatedChildData;
                }
            }

            $parsedStory['stories'] = $this->updateStoryOrder(
                $parsedStory['stories'],
            );

            $fileData = json_encode($parsedStory, JSON_PRETTY_PRINT);

            $this->info('Story created for: ' . $componentPath);

            $this->filesystem->put($storyPath, $fileData);
        } else {
            // if story file doesn't exist
            $storyData = [
                'path' => $relativePath,
                'children' => [
                    [
                        'name' => $filename,
                        'path' => $componentPath,
                        'options' => $this->getStoryOptions($component),
                    ],
                ],
            ];

            $template = $this->buildStoryTemplate($storyData);

            $fileData = json_encode($template, JSON_PRETTY_PRINT);

            $storyPathInfo = pathinfo($storyPath);
            $storyDir = $storyPathInfo['dirname'];

            $this->filesystem->ensureDirectoryExists($storyDir);

            $this->info('Story created for: ' . $componentPath);

            $this->filesystem->put($storyPath, $fileData);
        }
    }

    /**
     * @return void
     */
    private function handleAllComponents($files = null)
    {
        $this->filesystem->cleanDirectory($this->packageStoriesPath);
        $files = $this->filesystem->allfiles($this->storyViewsPath);
        $watch = $this->option('watch');

        $groups = $this->createGroups($files);

        $progressBar = $this->output->createProgressBar(1);
        $progressBar->setFormat('%current%/%max% [%bar%] %message%');

        foreach ($groups as $group) {
            //replace backslashes with forward slashes
            $group['path'] = str_replace('\\', '/', $group['path']);
            $template = $this->buildStoryTemplate($group);
            $storyFilePath =
                $this->packageStoriesPath .
                '/' .
                $group['path'] .
                '.stories.json';
            $storyPath = Str::beforeLast($storyFilePath, '/');
            $fileData = json_encode($template, JSON_PRETTY_PRINT);

            $this->info('');
            $progressBar->setMessage(
                'Creating stories for ' . $group['path'] . '...',
            );
            $progressBar->advance();

            $this->filesystem->ensureDirectoryExists($storyPath);

            $this->filesystem->put($storyFilePath, $fileData);
        }

        $this->info('');
        $progressBar->setMessage(
            'Stories created. Run blast:launch to init Storybook.',
        );
        $progressBar->finish();

        if ($watch) {
            $this->runProcessInBlast(['npm', 'run', 'watch-components'], true, [
                'PROJECTPATH' => base_path(),
                'COMPONENTPATH' => base_path('resources/views/stories'),
            ]);
        }
    }

    /**
     * @return array
     */
    private function createGroups($files = null)
    {
        $groups = [];

        if ($files) {
            foreach ($files as $file) {
                $filename = $file->getFilename();

                if (Str::endsWith($filename, '.blade.php')) {
                    $relativePathname = $file->getRelativePathname();
                    $relativePath = $file->getRelativePath();
                    $pathname = $file->getPathname();
                    $storyName =
                        $relativePath == '' ? $filename : $relativePath;

                    // if the view is in the folder root, add it to a generic 'components' directory
                    $storyPath = $relativePath
                        ? $relativePath
                        : str_replace('.blade.php', '', $filename);

                    $childData = [
                        'name' => $filename,
                        'path' => $relativePathname,
                        'options' => $this->getStoryOptions($pathname),
                    ];

                    if (Arr::has($groups, $storyName)) {
                        $groups[$storyName]['children'][] = $childData;
                    } else {
                        $groups[$storyName] = [
                            'path' => $storyPath,
                            'docs' => $this->getDocs($file->getPath()),
                            'children' => [$childData],
                        ];
                    }
                }
            }
        }

        return $groups;
    }

    /**
     * @return void
     */
    private function buildStoryTemplate($item)
    {
        $child_stories = $this->updateStoryOrder(
            array_map([$this, 'buildChildTemplate'], $item['children']),
        );

        $data = [
            'title' => ucwords($item['path'], '/'),
            'parameters' => [],
            'stories' => $child_stories,
        ];

        if (Arr::has($item, 'docs')) {
            $data['parameters']['notes'] = $item['docs'];
        }

        return $data;
    }

    /**
     * @return void
     */
    private function buildChildTemplate($item)
    {
        $data = [
            'name' => ucwords(
                str_replace('.blade.php', '', $item['name']),
                '/',
            ),
            'parameters' => [
                'server' => [
                    'id' => str_replace('.blade.php', '', $item['path']),
                ],
                'componentSource' => [
                    'code' => $this->getCodeSnippet($item['path']),
                ],
                'docs' => [
                    'source' => [
                        'code' => $this->getCodeSnippet($item['path']),
                    ],
                ],
                'actions' => [
                    'handles' => $this->getEvents($item),
                ],
            ],
        ];

        // build options array
        if (Arr::has($item, 'options')) {
            $options = $item['options'];

            if (Arr::has($options, 'preset')) {
                $preset = $this->dataStore->get($options['preset']);

                if (is_array($preset) && !empty($preset)) {
                    foreach ($preset as $key => $settings) {
                        if (is_array($settings)) {
                            $options[$key] = array_merge(
                                $settings,
                                $options[$key] ?? [],
                            );
                        } else {
                            if (!Arr::has($options, $key)) {
                                $options[$key] = $settings;
                            }
                        }
                    }
                }
            }

            if (Arr::has($options, 'presetArgs')) {
                $presetArgs = $options['presetArgs'];

                foreach ($presetArgs as $key => $preset) {
                    if (is_array($preset)) {
                        $args = array_map(function ($item) {
                            return $this->dataStore->get($item)['args'] ?? [];
                        }, $preset);
                    } else {
                        $args = $this->dataStore->get($preset)['args'] ?? [];
                    }

                    $options['args'][$key] = $args;
                }
            }

            if (Arr::has($options, 'name')) {
                $data['name'] = $options['name'];
            }

            if (Arr::has($options, 'status')) {
                $data['parameters']['status'] = [
                    'type' => $options['status'],
                ];
            }

            if (Arr::has($options, 'layout')) {
                $data['parameters']['layout'] = $options['layout'];
            }

            if (Arr::has($options, 'args')) {
                $data['args'] = $options['args'];
            }

            if (Arr::has($options, 'argTypes')) {
                $data['argTypes'] = $options['argTypes'];
            }
            
            //check if the options has 'attributes' set to true, call addAttributesArgument if so
            if (Arr::has($options, 'attributes') && $options['attributes']) {
                $data['args']['attributes'] = ['val' => ''];
                $data['argTypes']['attributes'] = $this->generateAttributesArgument();
            }

            if (Arr::has($options, 'design')) {
                $data['parameters']['design'] = [
                    'type' => 'figma',
                    'url' => $options['design'],
                ];
            }

            if (Arr::has($options, 'order')) {
                $data['order'] = $options['order'];
            }

            $defaultViewMode = config(
                'blast.storybook_default_view_mode',
                false,
            );
            if ($defaultViewMode || Arr::has($options, 'viewMode')) {
                $data['parameters']['viewMode'] =
                    $options['viewMode'] ?? $defaultViewMode;
            }

            if (Arr::has($options, 'assetGroup')) {
                $data['args']['assetGroup'] = $options['assetGroup'];

                if (!Arr::has($data['argTypes'], 'assetGroup')) {
                    $data['argTypes']['assetGroup'] = [
                        'table' => ['disable' => true],
                    ];
                }
            }
        }

        $data['hash'] = $this->getBladeChecksum(
            $item['path'],
            $data['args'] ?? [],
        );

        return $data;
    }

    /**
     * @return string
     */
    private function getBladeChecksum($filepath, $bladeArgs = [])
    {
        if (!Str::endsWith($filepath, '.blade.php')) {
            return '';
        }

        $bladePath =
            'stories.' .
            str_replace('/', '.', str_replace('.blade.php', '', $filepath));
        
        //if we have attributes in bladeArgs - convert to ComponentAttributeBag
        if (Arr::has($bladeArgs, 'attributes')) {
            $bladeArgs['attributes'] = new \Illuminate\View\ComponentAttributeBag(
                $bladeArgs['attributes'],
            );
        }

        return md5(view($bladePath, $bladeArgs)->render());
    }

    /**
     * @return void
     */
    private function getStoryOptions($filepath)
    {
        if (!$this->filesystem->exists($filepath)) {
            return [];
        }

        $contents = $this->filesystem->get($filepath);

        // Regexp modifiers:
        //   `s`  allows newlines as part of the `.*` match
        //   `U`  stops the match at the first closing parenthesis
        preg_match('/@storybook[ \t]*\(\[(.*)\]\)/sU', $contents, $matches);

        if (!filled($matches)) {
            return [];
        }

        $parsedOptions = eval("return [{$matches[1]}];");

        return $parsedOptions ?: [];
    }

    /**
     * @return void
     */
    private function getDocs($filepath)
    {
        $readme = $filepath . '/README.md';

        if ($this->filesystem->exists($readme)) {
            return $this->filesystem->get($readme);
        }

        return false;
    }

    private function updateStoryOrder($stories)
    {
        // sort by custom order. Fall back to alphabetical by story name
        return array_values(
            Arr::sort($stories, function ($story) {
                return $story['order'] ?? $story['name'];
            }),
        );
    }

    private function getCodeSnippet($filepath)
    {
        $filepath =
            $this->storyViewsPath . '/' . Str::finish($filepath, '.blade.php');

        if (!$this->filesystem->exists($filepath)) {
            return false;
        }

        $contents = $this->filesystem->get($filepath);

        $snippet = preg_replace('/@storybook\(\[(.*)\]\)/sU', '', $contents);

        return trim($snippet);
    }
    
    /**
     * Generate the attributes argument to the story args.
     * This is used to pass attributes to the component.
     * @return array
     */
    private function generateAttributesArgument()
    {
        return [
            "name" => "attributes",
            "type" => ["name" => "array", "required" => false],
            "description" => "An array of custom attributes that can be applied to the class.",
            "control" => ["type" => "object"]
        ];
    }

    private function getEvents(array $item): array
    {
        if (Arr::has($item, 'options.actions.handles')) {
            return $item['options']['actions']['handles'];
        } else {
            return [];
        }
    }
}
