@storybook([
    'layout' => 'fullscreen',
    'args' => [
        'type' => 'all'
    ],
    'argTypes' => [
        'type' => [
            'control' => 'select',
            'options' => [
                'All' => 'all',
                'Shades' => 'shades',
                'Text' => 'text',
                'Background' => 'bg',
                'Border' => 'border',
            ]
        ]
    ]
])
<x-docs-page
    title="Colors"
    description="Documentation for the project's colors"
>
    <x-ui-colors :type="$type" />
</x-docs-page>
