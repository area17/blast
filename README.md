# Blast — Storybook for Laravel Blade 🚀

## What is Blast?

Blast is a low maintenance component library using Storybook Server, built to integrate into your Laravel apps.

Blast allows you to render examples of your app's components using the blade templating engine using Storybook Server within your Laravel app.

## Install

```bash
composer require area17/blast
```

You may need to configure your app's assets in `config/blast.php` after install. To publish the configuration file, use:

```bash
php artisan vendor:publish --provider="A17\Blast\BlastServiceProvider" --tag="blast-config"
```

## Start Storybook

From your app's root directory run:

```bash
php artisan blast:launch
```

This will install all of the dependencies, generate stories and start a Storybook instance, as well as a watch task so updates to `.md` and `.blade.php` files in `resources/views/stories` and `.php` files in `resources/views/stories/data` will automatically regenerate the stories and update Storybook.

### Options

-   `--noInstall` - skip installing dependencies
-   `--noGenerate` - skip auto-generating stories based on existing components

## Generating Stories

Blast can also generate stories outside of the `launch` task. You can do this by running:

```bash
php artisan blast:generate-stories
```

### Options

-   `--watch` - watches the story blade files and updates stories

## Storybook Configuration

Global configuration can be done through the `config/blast.php`.

Blast uses the `public_path()` to reference any static assets. This means that any assets in that directory will be available during developement as well as static builds published to the public directory using the `blast:publish` task.

### Options

#### `storybook_server_url`

The route Storybook Server uses to render components. You shouldn't need to change this as it isn't ever visible on the FE.

Default: `config('app.url') . '/storybook_preview'`

#### `storybook_theme`

The array of theme options used by Storybook. More info [here](https://storybook.js.org/docs/react/configure/theming).

Default: `[]`

#### `canvas_bg_color`

Set the background color of the component canvas area. The Storybook theme doesn't allow this without also changing the background of other areas of the UI.

Default: `''`

#### `assets`

An array of urls to the `css` and `js` used by your components. The `css` and `js` urls are seperated out as the `css` is included in the head and the `js` is included before the closing `body` tag. You will most likely need to configure this after installing the package.

Default: `[ 'css' => [], 'js' => [], ]`

#### `storybook_statuses`

Blast ships with the [Status Addon](https://storybook.js.org/addons/@etchteam/storybook-addon-status) by Etch. This allows you to add custom status indicators to each component. This option allows you to customise these status indicators. More information on this can be found in the Custom Status section below.

Default:

```
[
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
]
```

#### `build_timeout`

Set a custom timeout for tasks in `launch` and `generate-stories`

Default: `300`

#### `vendor_path`

The relative path to the Blast package directory

Default: `vendor/area17/blast`

#### `components`

An array of custom components used by Blast.

Default: `[ 'docs-page' => Components\DocsPages\DocsPage::class ]`

## Story Configuration

There are certain Storybook elements you can configure from within your story blade files. You can do this by adding the `@storybook` directive to the top of your files:

```php
@storybook([
    'preset' => 'file.option'
    'name' => 'Component Name',
    'layout' => 'fullscreen',
    'status' => 'stable',
    'design' => "https://www.figma.com/file/LKQ4FJ4bTn\CSjedbRpk931/Sample-File",
    'args' => [
        'label' => 'Lorem Ipsum',
        'icon' => 'lorem-icon-dolor'
    ],
    'argTypes' => [
        'icon' =>[
            'options' => [
                'lorem-icon-dolor', 'another-icon'
            ],
            'control' => [
                'type' => 'select'
            ]
        ]
    ]
])
```

The supported options for this directive are:

-   `preset` - Use a preset as the base for the component story. Setting options in this directive will override the preset
-   `name` - Overrides the auto generated name in the Storybook sidebar.
-   `layout` - Set the component layout in canvas area. Options are `fullscreen`, `padded`, `centered` (default).
-   `status` - adds a status badge to the component story. Can be configured in the package config. See below for more info.
-   `design` - a Figma url for the component
-   `args` - an array of static data used to create storybook fields. You can read more about that [here](https://github.com/storybookjs/storybook/tree/main/app/server#server-rendering). The keys in the array are passed to the blade view and updated when the fields are updated in storybook.
-   `argTypes` - an array to define the args used for the controls. You can read more about them [here](https://storybook.js.org/docs/react/api/argtypes)

## Demo Components

Running `php artisan blast:demo` will create all the files needed to display a demo component. It creates files in your `resources/views/components` and `resources/views/stories` directories and generates the stories.

It can be run alongside the `php artisan blast:launch` task or you can run the demo task and then the `launch` task after to init Storybook.

## Presetting story options

You can create preset options for components to reuse throughtout your storybook instance.

The preset options use the same structure as Laravel config files:

```php
return [
    'primary' => [
        'args' => [
            'href' => '#',
            'label' => 'Primary',
        ],
    ],
    'primaryIcon' => [
        'args' => [
            'label' => 'Primary',
            'icon' => 'search-24',
            'iconPosition' => 'after',
        ],
        'argTypes' => [
            'icon' => [
                'control' => 'select',
                'options' => ['search-24', 'chevron-right-24', 'external-24'],
            ],
            'iconPosition' => [
                'control' => 'radio',
                'options' => ['Before' => 'before', 'After' => 'after'],
            ],
        ],
    ],
];
```

You can preset any of the options available in the `@storybook` directive.

To use the preset, set the `preset` option to the array path (using "dot" notation) where the first part is the name of the file followed by the option you wish to access.

```php
@storybook([
    'preset' => 'button.primary',
    'args' => [
        'label' => 'Read More',
    ],
]);
```

In this example it would update the label from 'Primary' to 'Read More'.

### Presetting data

In some instances it is beneficial to reuse data from other components in a new component. For example, a post list may use data for multiple post components.

To do this, you can reference the data in your new component's data file in a similar way to how you would set the preset in your story.

Use the `presetArgs` key to define the args with which you would like to data from another component. You can set the presets to either an array of references, or a single reference.

The example below creates the `items` array used in a `card-list` component using data from the `card` stories.

```php
// stories/data/card.php
return [
    'post' => [
        'args' => [
            'href' => '#',
            'title' => 'Euismod Vulputate',
            'subtitle' => 'Purus Malesuada',
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum id ligula porta felis euismod semper.'
        ]
    ],
    'post_alt' => [
        'args' => [
            'href' => '#',
            'title' => 'Cursus Aenean Quam',
            'subtitle' => 'Pharetra Quam',
            'description' => 'Etiam porta sem malesuada magna mollis euismod.',
        ]
    ],
    'post_alt_2' => [
        'args' => [
            'href' => '#',
            'title' => 'Etiam Cras Euismod',
            'subtitle' => 'Risus Etiam Pharetra Fusce',
            'description' => 'Maecenas faucibus mollis interdum. Vestibulum id ligula porta felis euismod semper.',
        ]
    ]
];

// stories/data/card-list.php
return [
    'posts' => [
        'presetArgs' => [
            'items' => [
                'card.post_alt_2',
                'card.post_alt',
                'card.post'
            ]
        ]
    ]
];

// output stories.json
"args": {
    "items": [
        {
            "href": "#",
            "title": "Etiam Cras Euismod",
            "subtitle": "Risus Etiam Pharetra Fusce",
            "description": "Maecenas faucibus mollis interdum. Vestibulum id ligula porta felis euismod semper."
        },
        {
            "href": "#",
            "title": "Cursus Aenean Quam",
            "subtitle": "Pharetra Quam",
            "description": "Etiam porta sem malesuada magna mollis euismod."
        },
        {
            "href": "#",
            "title": "Euismod Vulputate",
            "subtitle": "Purus Malesuada",
            "description": "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum id ligula porta felis euismod semper."
        }
    ]
},
```

## Custom Status

Blast comes with 4 preset statuses to use in your stories - `deprecated`, `wip`, `readyForQA` and `stable`. You can define custom statuses in `config/blast.php` by passing and array of statuses the `storybook_statuses` config. For example:

```php
'storybook_statuses' => [
    "phase1" => [
      "background" => '#333333',
      "color" => '#ffffff',
      "description" => 'This component is part of phase 1',
    ]
]
```

More infomation on this addon can be found [here](https://storybook.js.org/addons/@etchteam/storybook-addon-status).

Note: Defining custom statuses will override the existing statuses.

## Documentation

Adding a `README.md` to your storybook blade directory will allow you to add notes to the Docs tab for each component in Storybook. The content of the markdown file will be output above the auto-generated Storybook content.

## Publish Static Storybook

Blast can build a static Storybook app and publish it to your public folder. You do this by running:

```bash
php artisan blast:publish
```

### Options

-   `--o, --output-dir` - the directory where to store built files relative to your `public` directory
-   `--s, --static-dir` - the directory where to load static files from, comma-separated list relative to your project root directory

## Troubleshooting

If you see a `Failed to fetch` message when viewing your stories you will need to go to the path that Storybook is trying to load (open dev tools > network and right click the failed path and open in a new tab) and debug there. Any php errors or `dd` will trigger the `Failed to fetch` message.

## Known Issues

-   Renaming the story blade files can sometimes result in the story for that component being duplicated. You can work around this by running `php artisan blast:generate-stories`
