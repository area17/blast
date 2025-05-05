<?php

namespace A17\Blast\Traits;

use Symfony\Component\Process\Process;
use Illuminate\Support\Str;

trait Helpers
{
    protected $storybookDefaultVersion = '7.1.1';

    protected $storybookInstallVersion;

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
        $updateAddons = true;

        if ($npmInstall || (!$npmInstall && !$depsInstalled)) {
            $this->runProcessInBlast(
                ['npm', 'ci', '--omit=dev', '--ignore-scripts'],
                false,
                null,
                true,
            );

            $this->installStorybook($this->storybookInstallVersion);

            $this->installAddons();
        } else {
            if ($updateAddons) {
                $this->installAddons();
            }

            if ($updateStorybook) {
                $this->installStorybook($this->storybookInstallVersion);
            }
        }
    }

    private function installStorybook($storybookVersion)
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

        $deps = [
            "@storybook/addon-a11y@$this->storybookInstallVersion",
            "@storybook/addon-actions@$this->storybookInstallVersion",
            "@storybook/addon-docs@$this->storybookInstallVersion",
            "@storybook/addon-essentials@$this->storybookInstallVersion",
            "@storybook/addon-links@$this->storybookInstallVersion",
            "storybook@$this->storybookInstallVersion",
            "@storybook/server-webpack5@$this->storybookInstallVersion",
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

    private function getInstalledPackageVersion($package)
    {
        $version = false;
        $rawOutput = $this->runProcessInBlast(
            ['npm', 'list', $package, '--json'],
            false,
            null,
            false,
            true,
        );
        $data = json_decode($rawOutput, true);

        if (isset($data['dependencies'][$package])) {
            $version = $data['dependencies'][$package]['version'];
        }

        return $version;
    }

    private function checkStorybookVersions($storybookVersion)
    {
        // check if version matches installed version
        $installedStorybookVersion = $this->getInstalledPackageVersion(
            'storybook',
        );

        if ($installedStorybookVersion !== $this->storybookInstallVersion) {
            $this->newLine();
            $this->info('Storybook version mismatch');
            $this->info("Installed: $installedStorybookVersion");
            $this->info("To Install: $this->storybookInstallVersion");

            return true;
        }

        return false;
    }

    private function storybookConfigPublished()
    {
        $projectMainConfigPath = base_path('.storybook/main.js');

        return $this->filesystem->exists($projectMainConfigPath);
    }

    private function storybookConfigPath($path = false)
    {
        $configPublished = $this->storybookConfigPublished();
        $configPath = $configPublished
            ? base_path('.storybook')
            : $this->vendorPath . '/.storybook';

        if ($path) {
            $path = Str::start($path, '/');
        }
        return Str::of($configPath)->finish($path);
    }

    private function installAddons()
    {
        $storybookConfigPublished = $this->storybookConfigPublished();
        $mainJsPath = $this->storybookConfigPath('main.js');
        $mainJsContents = $this->filesystem->get($mainJsPath);
        $addons = config('blast.storybook_addons');
        $installedAddons = [];

        if (!$addons) {
            return 0;
        }

        $this->newLine();
        foreach ($addons as $addon) {
            $this->info('Found custom addon - ' . $addon);

            $addonInstalled = $this->getInstalledPackageVersion($addon);

            if (!$addonInstalled) {
                $this->info('Installing ' . $addon);

                $this->runProcessInBlast(['npm', 'install', $addon]);
            } else {
                $this->info('Addon already installed. Skipping installation.');
            }

            if (!Str::contains($mainJsContents, $addon)) {
                $this->info('Addon missing from .storybook/main.js. Adding.');

                $addonName = Str::of($addon);
                if ($storybookConfigPublished) {
                    $addonName = $addonName->start(
                        '../vendor/area17/blast/node_modules/',
                    );
                }

                $installedAddons[] = $addonName->start("'")->finish("'");
            }
        }

        if (filled($installedAddons)) {
            $this->filesystem->replaceInFile(
                'addons: [',
                "addons: [\n" . implode(",\n", $installedAddons) . ',',
                $mainJsPath,
            );
        }

        $this->info('Addons installed');
    }
}
