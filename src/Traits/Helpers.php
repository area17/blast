<?php

namespace A17\Blast\Traits;

use Symfony\Component\Process\Process;
use Illuminate\Support\Str;

trait Helpers
{
    /**
     * @return void
     */
    private function runProcessInBlast(
        array $command,
        $disableTimeout = false,
        $envVars = null
    ) {
        $process = new Process($command, $this->vendorPath, $envVars);
        $process->setTty(Process::isTtySupported());

        if ($disableTimeout) {
            $process->setTimeout(null);
        } else {
            $process->setTimeout(config('blast.build_timeout', 300));
        }

        $process->enableOutput();
        $process->mustRun();
    }

    /**
     * @return void
     */
    private function CopyDirectory($from, $to, $cleanDir = false)
    {
        $this->filesystem->ensureDirectoryExists($to);

        if ($cleanDir) {
            $this->filesystem->cleanDirectory($to);
        }

        if ($this->filesystem->exists($from)) {
            $this->filesystem->copyDirectory($from, $to);
        }
    }

    /**
     * Returns the full vendor_path for Blast.
     *
     * @return string
     */
    private function getVendorPath()
    {
        $vendorPath = config('blast.vendor_path');

        if (Str::startsWith($vendorPath, '/')) {
            return $vendorPath;
        }

        return base_path($vendorPath);
    }

    private function dependenciesInstalled()
    {
        return $this->filesystem->exists(
            $this->vendorPath . '/node_modules/@storybook',
        );
    }

    private function getInstallMessage($npmInstall)
    {
        $depsInstalled = $this->dependenciesInstalled();

        return ($npmInstall || (!$npmInstall && !$depsInstalled)
            ? 'Installing'
            : 'Reusing') . ' npm dependencies...';
    }

    private function installDependencies($npmInstall)
    {
        $depsInstalled = $this->dependenciesInstalled();

        if ($npmInstall || (!$npmInstall && !$depsInstalled)) {
            $this->runProcessInBlast([
                'npm',
                'ci',
                '--production',
                '--ignore-scripts',
            ]);
        }
    }
}
