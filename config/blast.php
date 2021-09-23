<?php

return [
    'storybook_server_url' =>
        env('STORYBOOK_SERVER_HOST', env('APP_URL')) . '/storybook_preview',

    // See https://storybook.js.org/docs/react/configure/theming for available options
    'storybook_theme' => [],

    // set the background color of the storybook canvas area
    'canvas_bg_color' => '',

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
