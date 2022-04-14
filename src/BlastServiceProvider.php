<?php

namespace A17\Blast;

use A17\Blast\Commands\Demo;
use A17\Blast\Commands\GenerateStories;
use A17\Blast\Commands\GenerateUIDocs;
use A17\Blast\Commands\Launch;
use A17\Blast\Commands\Publish;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;
use Illuminate\Support\Str;
use A17\Blast\Support\Transformer;

final class BlastServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/blast.php', 'blast');
        $this->registerCommands();
    }

    public function boot(): void
    {
        $this->bootResources();
        $this->bootBladeComponents();
        $this->bootBladeDirectives();
        $this->bootRoutes();
        $this->bootPublishing();
        $this->setAssetsFromMix();
        $this->bootTransformer();
    }

    private function registerCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                Demo::class,
                GenerateStories::class,
                GenerateUIDocs::class,
                Launch::class,
                Publish::class,
            ]);
        }
    }

    private function bootResources(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'blast');
    }

    private function bootRoutes(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
    }

    private function bootBladeDirectives(): void
    {
        Blade::directive('storybook', function () {
            return '';
        });

        Blade::directive('transformer', function ($contents) {
            return app(Transformer::class)->compileTransformer($contents);
        });
    }

    private function bootBladeComponents(): void
    {
        $this->callAfterResolving(BladeCompiler::class, function (
            BladeCompiler $blade
        ) {
            $prefix = config('blast.prefix', '');

            foreach (config('blast.components', []) as $alias => $component) {
                $blade->component($component, $alias, $prefix);
            }
        });
    }

    private function bootPublishing(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes(
                [
                    __DIR__ . '/../config/blast.php' => $this->app->configPath(
                        'blast.php',
                    ),
                ],
                'blast-config',
            );

            $this->publishes(
                [
                    __DIR__ . '/../resources/views' => $this->app->resourcePath(
                        'views/vendor/blast',
                    ),
                ],
                'blast-views',
            );

            $this->publishes(
                [
                    __DIR__ . '/../public' => public_path('blast'),
                ],
                'blast-assets',
            );
        }
    }

    private function setAssetsFromMix(): void
    {
        // bail early if autoload disabled or assets are already set
        if (!config('blast.autoload_assets') || $this->areAssetsSet()) {
            return;
        }

        $assets = [
            'css' => [],
            'js' => [],
        ];

        $mix_manifest_path = public_path('mix-manifest.json');

        // if the mix manifest exists, automatically load assets
        if (is_file($mix_manifest_path)) {
            $mix_manifest = json_decode(file_get_contents($mix_manifest_path));

            foreach ($mix_manifest as $key => $asset) {
                if (Str::endsWith($key, '.js')) {
                    $assets['js'][] = config('app.url') . $asset;
                } elseif (Str::endsWith($key, '.css')) {
                    $assets['css'][] = config('app.url') . $asset;
                }
            }

            config(['blast.assets' => $assets]);
        }
    }

    private function areAssetsSet(): bool
    {
        $assets = config('blast.assets');

        // if the count is greater than 0, we can assume that assets have been set
        return count(array_filter($assets)) > 0;
    }

    public function bootTransformer()
    {
        $this->app->singleton(Transformer::class, function ($app) {
            return new Transformer();
        });
    }
}
