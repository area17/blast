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
        $envVars = null,
        $disableOutput = false,
    ) {
        $process = new Process($command, $this->vendorPath, $envVars);
        $process->setTty(Process::isTtySupported());

        if ($disableTimeout) {
            $process->setTimeout(null);
        } else {
            $process->setTimeout(config('blast.build_timeout', 300));
        }

        if ($disableOutput) {
            $process->disableOutput();
        } else {
            $process->enableOutput();
        }

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

    private function installDependencies($npmInstall, $storybookVersion)
    {
        $depsInstalled = $this->dependenciesInstalled();

        if ($npmInstall || (!$npmInstall && !$depsInstalled)) {
            $this->runProcessInBlast(
                ['npm', 'ci', '--omit=dev', '--ignore-scripts'],
                false,
                null,
                true,
            );

            $this->installStorybook($storybookVersion);
        }
    }

    private function installStorybook($storybookVersion)
    {
        $storybookDefaultVersion = '7.1.1';

        if (!$storybookVersion) {
            $this->error(
                "No Storybook version defined. Using default version - $storybookDefaultVersion",
            );

            $storybookVersion = $storybookDefaultVersion;
        }

        // check if version exists
        $this->info("Verifying Storybook @ $storybookVersion");

        try {
            $this->runProcessInBlast(
                [
                    'npm',
                    'view',
                    "storybook@$storybookVersion",
                    'version',
                    '--json',
                ],
                false,
                null,
                true,
            );

            $this->info('Verified');
        } catch (\Exception $e) {
            $this->error(
                "Problem verifying Storybook version. Using default version - $storybookDefaultVersion",
            );

            $storybookVersion = $storybookDefaultVersion;

            usleep(250000);
        }

        $this->info("Installing Storybook @ $storybookVersion");

        $deps = [
            "@storybook/addon-a11y@$storybookVersion",
            "@storybook/addon-actions@$storybookVersion",
            "@storybook/addon-docs@$storybookVersion",
            "@storybook/addon-essentials@$storybookVersion",
            "@storybook/addon-links@$storybookVersion",
            "storybook@$storybookVersion",
            "@storybook/server-webpack5@$storybookVersion",
        ];

        try {
            $this->runProcessInBlast(
                ['npm', 'install', ...$deps],
                false,
                null,
                true,
            );
        } catch (\Exception $e) {
            $this->error($e->getMessage());

            exit();
        }
    }
}
