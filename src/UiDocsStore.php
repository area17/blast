<?php

namespace A17\Blast;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Arr;
use A17\Blast\Traits\Helpers;

class UiDocsStore
{
    use Helpers;

    /**
     * @var array
     */
    protected $data;

    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * @param Filesystem $filesystem
     */
    public function __construct()
    {
        $this->data = [];
        $this->vendorPath = $this->getVendorPath();
        $this->configPath = $this->vendorPath . '/tmp/tailwind.config.php';
        $this->filesystem = new Filesystem();

        if (!$this->filesystem->exists($this->configPath)) {
            return 1;
        }

        $this->data = include $this->configPath;
    }

    public function get($key = null)
    {
        if ($key) {
            return collect(Arr::get($this->data, $key));
        }
    }
}
