<?php

namespace Tests;

use A17\Blast\BlastServiceProvider;
use Illuminate\Support\Facades\File;
use Orchestra\Testbench\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    /**
     * Get package providers.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            BlastServiceProvider::class,
        ];
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function defineEnvironment($app)
    {
        // Set absolute vendor_path for TestBench
        $app['config']->set('blast.vendor_path', dirname(__DIR__));
    }

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->cleanDirectories();
    }

    protected function cleanDirectories()
    {
        File::cleanDirectory(resource_path('views/components'));
        File::cleanDirectory(resource_path('views/stories'));
        File::cleanDirectory($this->vendorPath('stories'));
    }

    protected function copyStubs($filesMap)
    {
        foreach ($filesMap as $source => $destination) {
            File::ensureDirectoryExists(dirname($destination));

            File::copy(__DIR__ . '/Stubs/' . $source, $destination);
        }
    }

    protected function vendorPath($path)
    {
        return config('blast.vendor_path') . '/' . $path;
    }
}
