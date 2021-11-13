<?php

return [
    'storybook_server_url' =>
        env('STORYBOOK_SERVER_HOST', env('APP_URL')) . 'storybook_preview',
    
    /**
     * See https://storybook.js.org/docs/react/essentials/controls Set
     * to true to enable full documentation on the controls tab. 
     */
    'storybook_expanded_controls' => true,

    /**
     * See https://storybook.js.org/docs/react/configure/theming for
     * detail - dark, normal, custom. Dark and normal are out of
     * the box from @storybook-theming. For custom edit the
     * values in 'custom_theme' to create a custom theme
     */
    'storybook_theme' => 'custom',

    'storybook_custom_theme' => [
        'base' => 'lightgray',
        'colorPrimary' => 'cadetblue',
        'colorSecondary' => 'lightcoral',
        'appBg' => 'whitesmoke',
        'appContentBg' => 'gainsboro',
        'appBorderColor' => 'ghostwhite',
        'appBorderRadius' => 4,
        'fontBase' => '"Nunito", sans-serif',
        'fontCode' => 'monospace',
        'textColor' => 'darkslategrey',
        'textInverseColor' => 'darkgrey',
        'barTextColor' => 'white',
        'barSelectedColor' => 'ghostwhite',
        'barBg' => 'cadetblue',
        'inputBg' => 'ghostwhite',
        'inputBorder' => 'ghostwhite',
        'inputTextColor' => 'darkslategrey',
        'inputBorderRadius' => 4,
        'brandTitle' => 'Blast storybook for blade',
        'brandUrl' => '#',
        'brandImage' => 'https://i.gifer.com/nN2.gif'
    ],

    // dark or normal
    'storybook_docs_theme' => 'custom',

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

