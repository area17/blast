<?php

namespace A17\Blast\Controllers;

use App\Http\Controllers\Controller;
use A17\Blast\Traits\Helpers;
use Illuminate\Filesystem\Filesystem;

class StoryController extends Controller
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

        return view(
            'blast::storybook',
            ['component' => str_replace('/', '.', $name)] + $parsedArgs,
        );
    }
}
