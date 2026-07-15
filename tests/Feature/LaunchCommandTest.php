<?php

namespace A17\Blast\Tests\Feature;

use A17\Blast\Commands\Launch;
use Tests\TestCase;
use Illuminate\Filesystem\Filesystem;
use Mockery;

class LaunchCommandTest extends TestCase
{
    public function test_it_injects_bind_host_to_process_environment()
    {
        config(['blast.storybook_bind_host' => '192.168.1.99']);

        $filesystemMock = Mockery::mock(Filesystem::class);
        $filesystemMock
            ->shouldReceive('ensureDirectoryExists')
            ->andReturn(true);
        $filesystemMock->shouldReceive('put')->andReturn(true);

        $filesystemMock->shouldReceive('exists')->andReturn(false);

        $commandMock = Mockery::mock(
            Launch::class . '[installDependencies,runProcessInBlast]',
            [$filesystemMock],
        );
        $commandMock->shouldAllowMockingProtectedMethods();

        $commandMock->shouldReceive('installDependencies')->once();

        $commandMock
            ->shouldReceive('runProcessInBlast')
            ->once()
            ->withArgs(function ($command, $disableTimeout, $envVars) {
                $isCorrectCommand = $command === ['npm', 'run', 'storybook'];
                $hasCorrectHost =
                    isset($envVars['STORYBOOK_BIND_HOST']) &&
                    $envVars['STORYBOOK_BIND_HOST'] === '192.168.1.99';

                return $isCorrectCommand && $hasCorrectHost;
            });

        $this->app->instance(Launch::class, $commandMock);

        $this->artisan('blast:launch', [
            '--noGenerate' => true,
        ])->assertExitCode(0);
    }
}
