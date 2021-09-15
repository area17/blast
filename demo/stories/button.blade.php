@storybook([
    'name' => 'Primary Button',
    'status' => 'readyForQA',
    'design' => "https://www.figma.com/file/LKQ4FJ4bTnCSjedbRpk931/Sample-File",
    'args' => [
        'label' => 'Button',
        'href' => 'http://area17.com',
        'icon' => 'plus-24',
        'iconPosition' => 'after'
    ],
    'argTypes' => [
        'iconPosition' =>[
            'options' => [
                'before', 'after'
            ],
            'control' => [
                'type' => 'radio'
            ]
        ],
        'icon' =>[
            'options' => [
                'help-24', 'menu-24', 'plus-24'
            ],
            'control' => [
                'type' => 'select'

            ]
        ]
    ]
])

<x-blast-demo.button
    :href="$href"
    :icon="$icon"
    :icon-position="$iconPosition"
>
    {{ $label }}
</x-blast-demo.button>

<style>
    a,
    button {
        display: inline-flex;
        justify-items: center;
        align-items: center;
        padding: 16px 20px;
        border: 1px solid #121212;
        background: transparent;
        color: #121212;
        font-weight: 600;
        font-size: 18px;
        text-align: center;
        white-space: nowrap;
        transition: all 0.3s ease-in-out;
    }

    a:hover,
    button:hover {
        background: #121212;
        color: #fff;
    }

    a svg:first-child,
    button svg:first-child {
        margin-right: 16px;
    }

    a svg:last-child,
    button svg:last-child {
        margin-left: 16px;
    }
</style>
