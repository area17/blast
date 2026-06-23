<?php

namespace A17\Blast\Traits;

use Symfony\Component\Process\Process;
use Illuminate\Support\Str;

trait Helpers
{
    protected string $storybookDefaultVersion = '8.5.0';

    protected ?string $storybookInstallVersion = null;

    /**
     * @return void
     */
    private function runProcessInBlast(
        array $command,
        $disableTimeout = false,
        $envVars = null,
        $disableOutput = false,
        $disableTty = false,
    ) {
        $process = new Process($command, $this->vendorPath, $envVars);

        if ($disableTimeout) {
            $process->setTimeout(null);
        } else {
            $process->setTimeout(config('blast.build_timeout', 300));
        }

        if ($disableTty) {
            $process->setTty(false);
        } else {
            $process->setTty(Process::isTtySupported());
        }

        if ($disableOutput) {
            $process->disableOutput();
        } else {
            $process->enableOutput();
        }

        $process->run();

        if (!$disableOutput) {
            return $process->getOutput();
        }
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
        $this->storybookInstallVersion = config('blast.storybook_version');
        $depsInstalled = $this->dependenciesInstalled();
        $updateStorybook = $this->checkStorybookVersions(
            $this->storybookInstallVersion,
        );

        if ($npmInstall || (!$npmInstall && !$depsInstalled)) {
            $this->runProcessInBlast(
                ['npm', 'ci', '--omit=dev', '--ignore-scripts'],
                false,
                null,
                true,
            );

            $this->installStorybook($this->storybookInstallVersion);
        } else {
            if ($updateStorybook) {
                $this->installStorybook($this->storybookInstallVersion);
            }
        }
    }

    private function installStorybook(?string $storybookVersion): void
    {
        if (!$storybookVersion) {
            $this->error(
                "No Storybook version defined. Using default version - $this->storybookDefaultVersion",
            );

            $this->storybookInstallVersion = $this->storybookDefaultVersion;
        } else {
            $this->storybookInstallVersion = $storybookVersion;
        }

        // check if version exists
        $this->info("Verifying Storybook @ $this->storybookInstallVersion");

        try {
            $this->runProcessInBlast(
                [
                    'npm',
                    'view',
                    "storybook@$this->storybookInstallVersion",
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
                "Problem verifying Storybook version. Using default version - $this->storybookDefaultVersion",
            );

            $this->storybookInstallVersion = $this->storybookDefaultVersion;

            usleep(250000);
        }

        $this->info("Installing Storybook @ $this->storybookInstallVersion");

        $deps = $this->getStorybookDependencies($this->storybookInstallVersion);

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

    /**
     * Get Storybook dependencies based on major version.
     * Storybook 9+ consolidates many addons into core.
     */
    private function getStorybookDependencies(string $version): array
    {
        $majorVersion = $this->getStorybookMajorVersion($version);

        // Core packages required for all versions
        $deps = ["storybook@$version", "@storybook/server-webpack5@$version"];

        if ($majorVersion >= 9) {
            // Storybook 9+ has many addons consolidated into core
            // Only add essentials which includes most functionality
            $deps[] = "@storybook/addon-essentials@$version";
        } else {
            // Storybook 7.x and 8.x require separate addon packages
            $deps = array_merge($deps, [
                "@storybook/addon-a11y@$version",
                "@storybook/addon-actions@$version",
                "@storybook/addon-docs@$version",
                "@storybook/addon-essentials@$version",
                "@storybook/addon-links@$version",
            ]);
        }

        return $deps;
    }

    /**
     * Extract major version number from Storybook version string.
     */
    private function getStorybookMajorVersion(string $version): int
    {
        // Handle versions like "8.5.0", "9.0.0-alpha.1", etc.
        preg_match('/^(\d+)/', $version, $matches);

        return (int) ($matches[1] ?? 8);
    }

    private function getInstalledStorybookVersion()
    {
        $version = false;
        $rawOutput = $this->runProcessInBlast(
            ['npm', 'list', 'storybook', '--json'],
            false,
            null,
            false,
            true,
        );
        $data = json_decode($rawOutput, true);

        if (isset($data['dependencies']['storybook'])) {
            $version = $data['dependencies']['storybook']['version'];
        }

        return $version;
    }

    private function checkStorybookVersions($storybookVersion)
    {
        // check if version matches installed version
        $installedStorybookVersion = $this->getInstalledStorybookVersion();

        if ($installedStorybookVersion !== $this->storybookInstallVersion) {
            $this->newLine();
            $this->info('Storybook version mismatch');
            $this->info("Installed: $installedStorybookVersion");
            $this->info("To Install: $this->storybookInstallVersion");

            return true;
        }

        return false;
    }
}
