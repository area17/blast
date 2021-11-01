<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

$sb_url = config('blast.storybook_server_url');
$app_url = config('app.url');

$sb_route = Str::remove($app_url, $sb_url);

Route::get($sb_route . '/{name?}', function ($name) {
    $parsedArgs = array_map(function ($arg) {
        $parsed = json_decode($arg, true);

        return $parsed ?? $arg;
    }, request()->all());

    $canvasBgColor = config('blast.canvas_bg_color') ?? null;
    $css = config('blast.assets.css') ?? [];
    $js = config('blast.assets.js') ?? [];
    $assetGroup = $parsedArgs['assetGroup'] ?? null;

    $parsedCss = [];
    $parsedJs = [];

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

    return view(
        'blast::storybook',
        [
            'component' => str_replace('/', '.', $name),
            'canvasBgColor' => $canvasBgColor,
            'css' => $parsedCss,
            'js' => $parsedJs,
        ] + $parsedArgs,
    );
})->where('name', '.*');
