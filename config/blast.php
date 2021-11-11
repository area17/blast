<?php

return [
    'storybook_server_url' =>
        env('STORYBOOK_SERVER_HOST', env('APP_URL')) . 'storybook_preview',
    
    // 
    'expanded_controls' => true,

    /**
     * See https://storybook.js.org/docs/react/configure/theming for detail
     * dark, normal, custom. Dark and Normal (light) are out of the box
     * from @storybook-theming. For Custom replace values in
     * 'custom_theme' to create a custom theme
     */
    'storybook_theme' => 'dark',

    // 'custom_theme' => [
    //     'base' => 'light',
    //     'colorPrimary' => 'hotpink',
    //     'colorSecondary' => 'deepskyblue',
    //     'appBg' => 'white',
    //     'appContentBg' => 'silver',
    //     'appBorderColor' => 'grey',
    //     'appBorderRadius' => 4,
    //     'fontBase' => '"Open Sans", sans-serif',
    //     'fontCode' => 'monospace',
    //     'textColor' => 'black',
    //     'textInverseColor' => 'rgba(255,255,255,0.9)',
    //     'barTextColor' => 'silver',
    //     'barSelectedColor' => 'black',
    //     'barBg' => 'hotpink',
    //     'inputBg' => 'white',
    //     'inputBorder' => 'silver',
    //     'inputTextColor' => 'black',
    //     'inputBorderRadius' => 4,
    //     'brandTitle' => 'My custom storybook',
    //     'brandUrl' => 'https://example.com',
    //     'brandImage' => 'https://place-hold.it/350x150'
    // ],

    // set the background color of the storybook canvas area
    'canvas_bg_color' => '',

    // Blast will attempt to autoload assets from a mix-manifest if the assets arrays are empty. This option allows you to disable that functionality
    'autoload_assets' => true,

    'assets' => [
        'css' => [],
        'js' => [],
    ],

    // See https://storybook.js.org/addons/@etchteam/storybook-addon-status/
    'storybook_statuses' => [
        'deprecated' => [
            'background' => '#e02929',
            'color' => '#ffffff',
            'description' =>
                'This component is deprecated and should no longer be used',
        ],
        'wip' => [
            'background' => '#f59506',
            'color' => '#ffffff',
            'description' => 'This component is a work in progress',
        ],
        'readyForQA' => [
            'background' => '#34aae5',
            'color' => '#ffffff',
            'description' => 'This component is complete and ready to qa',
        ],
        'stable' => [
            'background' => '#1bbb3f',
            'color' => '#ffffff',
            'description' => 'This component is stable and released',
        ],
    ],

    'build_timeout' => 300,

    'vendor_path' => 'vendor/area17/blast',

    'components' => [
        'docs-page' => \A17\Blast\Components\DocsPages\DocsPage::class,
    ],
];
