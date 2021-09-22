<?php

namespace A17\Blast\Traits;

use Symfony\Component\Process\Process;

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
        $process = new Process(
            $command,
            base_path($this->vendorPath),
            $envVars,
        );
        $process->setTty(Process::isTtySupported());

        if ($disableTimeout) {
            $process->setTimeout(null);
        } else {
            $process->setTimeout(config('blast.build_timeout', 300));
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
}
