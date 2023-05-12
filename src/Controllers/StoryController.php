<?php

namespace A17\Blast\Controllers;

use A17\Blast\Traits\Helpers;
use Illuminate\Filesystem\Filesystem;

class StoryController
{
    use Helpers;

    public function __invoke($name, Filesystem $filesystem)
    {
        $vendor_path = $this->getVendorPath();
        $file_check = $filesystem->exists($vendor_path . '/tmp/_blast');

        if ($file_check) {
            header('Access-Control-Allow-Origin: *');
        }

        $parsedArgs = array_map(function ($arg) {
            $parsed = json_decode($arg, true);

            return $parsed ?? $arg;
        }, request()->all());
        
        //if passedArgs contains 'attributes', convert it to a ComponentAttributeBag
        if (isset($parsedArgs['attributes'])) {
            $parsedArgs['attributes'] = new \Illuminate\View\ComponentAttributeBag(
                $parsedArgs['attributes'],
            );
        }

        $canvasBgColor = config('blast.canvas_bg_color') ?? null;
        $css = config('blast.assets.css') ?? [];
        $js = config('blast.assets.js') ?? [];
        $assetGroup = $parsedArgs['assetGroup'] ?? null;

        $parsedCss = [];
        $parsedJs = [];

        if (!empty($css)) {
            foreach ($css as $key => $asset) {
                if (is_string($key)) {
                    if (isset($assetGroup) && $key == $assetGroup) {
                        if (is_array($asset)) {
                            foreach ($asset as $key => $childAsset) {
                                $parsedCss[] = $childAsset;
                            }
                        } else {
                            $parsedCss[] = $asset;
                        }
                    }
                } else {
                    $parsedCss[] = $asset;
                }
            }
        }

        if (!empty($js)) {
            foreach ($js as $key => $asset) {
                if (is_string($key)) {
                    if (isset($assetGroup) && $key == $assetGroup) {
                        if (is_array($asset)) {
                            foreach ($asset as $key => $childAsset) {
                                $parsedJs[] = $childAsset;
                            }
                        } else {
                            $parsedJs[] = $asset;
                        }
                    }
                } else {
                    $parsedJs[] = $asset;
                }
            }
        }

        return view(
            'blast::storybook',
            [
                'component' => str_replace('/', '.', $name),
                'canvasBgColor' => $canvasBgColor,
                'css' => $parsedCss,
                'js' => $parsedJs,
            ] + $parsedArgs,
        );
    }
}
