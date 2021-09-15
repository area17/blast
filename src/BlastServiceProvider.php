<?php

namespace A17\Blast;

use A17\Blast\Commands\Demo;
use A17\Blast\Commands\GenerateStories;
use A17\Blast\Commands\Launch;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;

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
    }

    private function registerCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                Demo::class,
                GenerateStories::class,
                Launch::class,
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
    }

    private function bootBladeComponents(): void
    {
        $this->callAfterResolving(BladeCompiler::class, function (
            BladeCompiler $blade,
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
}
