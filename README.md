# Blast — Storybook for Laravel Blade 🚀

<a href="https://github.com/area17/blast/actions"><img src="https://github.com/area17/blast/actions/workflows/phpunit.yml/badge.svg" alt="phpunit tests status"></a>
<a href="https://packagist.org/packages/area17/blast"><img src="https://poser.pugx.org/area17/blast/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/area17/blast"><img src="https://poser.pugx.org/area17/blast/license.svg" alt="License"></a>

## What is Blast?

Blast is a low maintenance component library using Storybook Server, built to integrate into your Laravel apps.

Blast allows you to render examples of your app's components using the blade templating engine using Storybook Server within your Laravel app.

We've published some articles to help you get started with Blast and it's features:

-   [Getting Started with Blast – Storybook for Laravel Blade](https://dev.to/area17/getting-started-with-blast-storybook-for-laravel-blade-c5c)
-   [Auto-visualizing Tailwind Tokens and Documenting Design Systems Props with Blast](https://dev.to/area17/documenting-your-design-system-in-blast-4ao6)

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

-   `--install` - force install dependencies
-   `--noGenerate` - skip auto-generating stories based on existing components
-   `--port` - port used to run Storybook

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

#### `storybook_version`

Allows the use of a different version of Storybook. Blast has only been tested up to 7.1.1.

Default: `'7.1.1'`

#### `storybook_server_url`

The route Storybook Server uses to render components. You shouldn't need to change this as it isn't ever visible on the FE.

Default: `config('app.url') . '/storybook_preview'`

#### `auto_documentation`

Blast can automatically generate documentation pages in the form of stories based on your Tailwind config. Use this array to specify which documentation pages to generate. All options are loaded by default.

Default:

```
[
    'border-radius',
    'border-width',
    'colors',
    'font-size',
    'font-weight',
    'height',
    'layout',
    'letter-spacing',
    'line-height',
    'max-height',
    'max-width',
    'min-height',
    'min-width',
    'opacity',
    'shadows',
    'spacing',
    'transition',
    'typesets',
    'width',
]

```

#### `tailwind_config_path`

The path to your Tailwind config file. Used to parse the auto-documentation.

Default: `base_path('tailwind.config.js')`

#### `storybook_expanded_controls`

See https://storybook.js.org/docs/react/essentials/controls Set to true to enable full documentation on the controls tab.
Enabling this feature will require configuration in the `@storybook` blade directive, see `description`, `defaultValue` and `table` array keys in the blade directive configuration.

Default: `true`

#### `storybook_theme`

The array of theme options used by Storybook. More info [here](https://storybook.js.org/docs/react/configure/theming).
The options are normal, dark or custom. Normal and dark themes are out of the box from the @storybook-theming addon.
To add a custom theme edit values in the `storybook_custom_theme` array in config/blast.php.

Default: `'normal'`

#### `storybook_docs_theme`

With the same options as `storybook_theme` this configures the theme applied to the docs tab.

Default: `'normal'`

#### `storybook_custom_theme`

An array passed to the `@storybook-theming` addon to create a custom theme. HTML color names, RGB and HEX colors are all supported.

#### `canvas_bg_color`

Set the background color of the component canvas area. The Storybook theme doesn't allow this without also changing the background of other areas of the UI.

Default: `''`

#### `autoload_assets`

Blast will attempt to autoload assets from a `mix-manifest.json` (Laravel Mix) or `manifest.json` (Vite - added in 1.7) if the assets arrays are empty. This option allows you to disable that functionality. Note that the Vite assets are only auto loaded from a prod build. If you want to use it with Vite's hot reloading, you will need to manually define it in the `asset` array using the full local url (eg. http://127.0.0.1:5173/resources/css/app.css), or you can publish and modify the `storybook.blade.php` view to use Laravel's `@vite` helper.

Default: `true`

#### `mix_manifest_path`

Allows you to customize the path to the mix-manifest file used to autoload assets.

Default: `public_path('mix-manifest.json')`

#### `vite_manifest_path`

Allows you to customize the path to the vite manifest file used to autoload assets.

Default: `public_path('build/manifest.json')`

#### `assets`

An array of urls to the `css` and `js` used by your components. The `css` and `js` urls are seperated out as the `css` is included in the head and the `js` is included before the closing `body` tag. `js` assets can have [types](https://developer.mozilla.org/en-US/docs/Web/HTML/Element/script/type) (default: `text/javascript`).

You can also group assets and specify which to use for different components.

```php
'assets' => [
    'css' => [
        'path/to/default.css', // default, loaded in all stories
        'blast' => 'path/to/blast.css', // load a single file
        'area17' => [ // use array to load multiple files
            'path/to/area17.css',
            'path/to/area17-other.css'
        ]
    ],
    'js' => [
        'path/to/default.js', // default, loaded in all stories
        [ // load as a module
            'path' => 'path/to/default.js',
            'type' => 'module'
        ]
        'blast' => 'path/to/blast.js', // load a single file
        'area17' => [ // use array to load multiple files
            'path/to/area17.js'
            'path/to/area17-other.js'
        ],
    ]
]
```

In your story blade file you would select the assets to use with `assetGroup`.

```php
@storybook([
    'assetGroup' => 'blast',
]);
```

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

#### `storybook_sort_order`

Define a custom order for the stories. Accepts an array of story names and can contain nested arrays to set the order of 2nd tier stories. More information can be found in the [official Storybook Docs](https://storybook.js.org/docs/react/writing-stories/naming-components-and-hierarchy#sorting-stories).

Default: `[]` (alphabetical)

#### `storybook_global_types`

The Global Types can be used, for example, to extend and edit the toolbar. The array of toolbars and globals options used by Storybook. More info [here](https://storybook.js.org/docs/react/essentials/toolbars-and-globals).

Default: `[]`

#### `storybook_default_view_mode`

Set the default view for each story to either the Canvas or Docs view. This can be overridden in each story using the `viewMode` prop in the `@storybook` directive. Use the value `story` for the canvas view and `docs` for the docs view. If set to `false` it will use the last used view when changing between stories.

Default: `false`

#### `build_timeout`

Set a custom timeout for tasks in `launch` and `generate-stories`

Default: `300`

#### `vendor_path`

The relative path to the Blast package directory

Default: `vendor/area17/blast`

#### `components`

An array of custom components used by Blast.

Default: `[ 'docs-page' => Components\DocsPages\DocsPage::class ]`

#### `storybook_viewports`

Configure custom viewports in the Storybook preview toolbar.

It supports an array with the structure found [in the Storybook docs](https://storybook.js.org/docs/react/essentials/viewport#add-new-devices) and it can also use your Tailwind config to generate the viewports for you by setting the value to `'tailwind'`. Defaults to `'tailwind'` but fails silently if blast can't find a Tailwind config. The viewports can be disabled by setting to `false`.

It supports the various ways you can define breakpoints in Tailwind using these rules:

-   If the value is a string, it uses that
-   If the value is an array with only a `min` **or** only a `max` it will use that value
-   If the value is an array with both a `min` **and** `max` value it will use the `min` value
-   `raw` values will be ignored

Default: `'tailwind'`

## Story Configuration

There are certain Storybook elements you can configure from within your story blade files. You can do this by adding the `@storybook` directive to the top of your files:

```php
@storybook([
    'preset' => 'file.option'
    'name' => 'Component Name',
    'layout' => 'fullscreen',
    'status' => 'stable',
    'order' => 1,
    'design' => "https://www.figma.com/file/LKQ4FJ4bTn\CSjedbRpk931/Sample-File",
    'design' => [
        [
            'name' => 'Foo',
            'type' => "figma",
            'url' => "https://www.figma.com/file/LKQ4FJ4bTn\CSjedbRpk931/Sample-File",
        ],
        [
            'name' => 'Bar',
            'type' => "link",
            'url' => "https://www.figma.com/file/LKQ4FJ4bTn\CSjedbRpk931/Sample-File",
        ],
    ],
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
            ],
            'description' => 'descriptive text',
            'defaultValue' => 'lorem-icon-dolor',
            'table' => [
                'type' => [
                    'summary' => 'string'
                ],
                'defaultValue' => [
                    'summary' => 'lorem-icon-dolor'
                ],
            ],
        ]
    ],
    'actions' => [
        'handles' => ['mouseover', 'click']
    ]
])
```

The supported options for this directive are:

-   `preset` - Use a preset as the base for the component story. Setting options in this directive will override the preset
-   `name` - Overrides the auto generated name in the Storybook sidebar.
-   `layout` - Set the component layout in canvas area. Options are `fullscreen`, `padded`, `centered` (default).
-   `status` - adds a status badge to the component story. Can be configured in the package config. See below for more info.
-   `order` - Customize the order of each story. Supports float values. Defaults to alphabetical order.
-   `design` - a Figma url for the component or array of design parameters. You can read more about the supported options [here](https://storybookjs.github.io/addon-designs)
-   `args` - an array of static data used to create storybook fields. You can read more about that [here](https://github.com/storybookjs/storybook/tree/main/app/server#server-rendering). The keys in the array are passed to the blade view and updated when the fields are updated in storybook.
-   `argTypes` - an array to define the args used for the controls. You can read more about them [here](https://storybook.js.org/docs/react/api/argtypes)
-   `actions.handles` - an array defining the events that are passed to the `@storybook-actions` addon. You can read more about actions [here](https://storybook.js.org/docs/react/essentials/actions) - See the Action Event Handlers heading.

## Customizing the story view

You can customize a lot of the story component view from within `config/blast.php` but if you need to take it a step futher you can publish the view to your application folder and modify it there.

```bash
php artisan vendor:publish --provider="A17\Blast\BlastServiceProvider" --tag="blast-views"
```

This will publish `storybook.blade.php` and all of the ui-docs components to `resources/views/vendor/blast`.

## Demo Components

Running `php artisan blast:demo` will create all the files needed to display a demo component. It creates files in your `resources/views/components` and `resources/views/stories` directories and generates the stories.

It can be run alongside the `php artisan blast:launch` task or you can run the demo task and then the `launch` task after to init Storybook.

## Presetting story options

You can create preset options for components to reuse throughout your storybook instance.

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

Adding a `README.md` to your storybook blade directory will allow you to add a documentation page for the component in Storybook. The content of the markdown file will be output above the auto-generated Storybook content.

You can also add a markdown file with the same name as your story file and it will add the documentation to component variation on the documentation page.

## Publish Static Storybook

Blast can build a static Storybook app and publish it to your public folder. You do this by running:

```bash
php artisan blast:publish
```

You can also publish to Chromatic using the `-t` or `--chromatic-token` option and your Chromatic project token. Note that it requires the `-u` or `--url` option to also be defined as a publicly accessible url as Storybook Server will use that to render the components in Chromatic.

## Generate Tailwind Documenatation Stories

Blast can automatically generate stories to visualize your Tailwind configuration. See 'auto_documentation' above to see how to configure which stories to generate.

```bash
php artisan blast:generate-docs
```

You can pass the option `--force` to automatically overwrite existing documenation stories or use the `--update-data` option to update the story data without copying any files (this option only works if you have already run the task before).

### Options

-   `--o, --output-dir` - the directory where to store built files relative to your `public` directory

## Publish Storybook Config

There may be times when you need to publish the storybook config files to your application directory (eg. changing Storybook versions). This copies the `.storybook` directory to your application root directory and updates the launch and publish tasks to use this directory for any Storybook configuration.

```bash
php artisan blast:publish-storybook-config
```

## Troubleshooting

If you see a `Failed to fetch` message when viewing your stories you will need to go to the path that Storybook is trying to load (open dev tools > network and right click the failed path and open in a new tab) and debug there. Any php errors or `dd` will trigger the `Failed to fetch` message.

## Known Issues

-   Renaming the story blade files can sometimes result in the story for that component being duplicated. You can work around this by running `php artisan blast:generate-stories`
