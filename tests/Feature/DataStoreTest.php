<?php

namespace Tests\Feature;

use A17\Blast\DataStore;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Tests\TestCase;

class DataStoreTest extends TestCase
{
    public function test_can_use_relative_path()
    {
        $path = 'resources/views/stories/non-standard-relative';
        $this->writeExampleDataFile($path);
        Config::set('blast.data_path', $path);
        $this->assertEquals('bar', app(DataStore::class)->get('example.dummy.args.foo'));
        $this->cleanup($path);
    }

    public function test_can_use_absolute_path()
    {
        $path = base_path('resources/views/stories/non-standard-absolute');
        $this->writeExampleDataFile($path);
        Config::set('blast.data_path', $path);
        $this->assertEquals('bar', app(DataStore::class)->get('example.dummy.args.foo'));
        $this->cleanup($path);
    }

    public function test_default_path()
    {
        $path = base_path('resources/views/stories/data');
        $this->writeExampleDataFile($path);
        $this->assertEquals('bar', app(DataStore::class)->get('example.dummy.args.foo'));
    }

    private function writeExampleDataFile(string $path)
    {
        if (! Str::startsWith($path, '/')) {
            $path = base_path($path);
        }
        File::ensureDirectoryExists($path);
        File::put($path . '/example.php', <<<PHP
<?php

return [
    'dummy' => [
        'args' => [
            'foo' => 'bar',
        ],
    ],
];
PHP
        );
    }

    private function cleanup(string $path)
    {
        if (! Str::startsWith($path, '/')) {
            $path = base_path($path);
        }
        File::deleteDirectory($path);
    }
}
