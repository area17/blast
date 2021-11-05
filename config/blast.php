<?php

return [
    'storybook_server_url' =>
        env('STORYBOOK_SERVER_HOST', env('APP_URL')) . '/storybook_preview',

    // See https://storybook.js.org/docs/react/configure/theming for available options
    'storybook_theme' => [],

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

    'storybook_global_types' => [],

    // set a global custom order for stories in the Storybook UI
    // More info - https://storybook.js.org/docs/react/writing-stories/naming-components-and-hierarchy#sorting-stories
    'storybook_sort_order' => [],

    'build_timeout' => 300,

    'vendor_path' => 'vendor/area17/blast',

    'components' => [
        'docs-page' => \A17\Blast\Components\DocsPages\DocsPage::class,
        'ui-colors' => \A17\Blast\Components\DocsPages\UiColors::class,
        'ui-spacing' => \A17\Blast\Components\DocsPages\UiSpacing::class,
        'ui-width' => \A17\Blast\Components\DocsPages\UiWidth::class,
        'ui-min-width' => \A17\Blast\Components\DocsPages\UiMinWidth::class,
        'ui-max-width' => \A17\Blast\Components\DocsPages\UiMaxWidth::class,
        'ui-height' => \A17\Blast\Components\DocsPages\UiHeight::class,
        'ui-min-height' => \A17\Blast\Components\DocsPages\UiMinHeight::class,
        'ui-max-height' => \A17\Blast\Components\DocsPages\UiMaxHeight::class,
        'ui-border-width' =>
            \A17\Blast\Components\DocsPages\UiBorderWidth::class,
        'ui-border-radius' =>
            \A17\Blast\Components\DocsPages\UiBorderRadius::class,
        'ui-opacity' => \A17\Blast\Components\DocsPages\UiOpacity::class,
        'ui-shadows' => \A17\Blast\Components\DocsPages\UiShadows::class,
    ],
];
