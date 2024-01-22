<?php

namespace A17\Blast\Traits;

use Illuminate\Support\Arr;
use Illuminate\Filesystem\Filesystem;

trait TailwindViewports
{
    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * @param Filesystem $filesystem
     */
    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    private function buildTailwindViewports($viewports = false)
    {
        if ($viewports === 'tailwind') {
            $parsedConfig = $this->vendorPath . '/tmp/tailwind.config.php';

            if (!$this->filesystem->exists($parsedConfig)) {
                return false;
            }

            $config = include $parsedConfig;

            $viewports = [];

            foreach ($config['theme']['screens'] as $key => $value) {
                $width = false;

                if (is_array($value) && !empty($value)) {
                    if (Arr::has($value, 'min')) {
                        $width = $value['min'];
                    } elseif (Arr::has($value, 'max')) {
                        $width = $value['max'];
                    }
                } elseif (is_string($value) && $value !== '0') {
                    $width = $value;
                }

                if ($width) {
                    $viewports[$key] = [
                        'name' => $key,
                        'styles' => [
                            'width' => $width,
                            'height' => '100%',
                        ],
                    ];
                }
            }

            return $viewports;
        } else {
            return $viewports;
        }

        return false;
    }
}
