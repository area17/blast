@storybook([
    'layout' => 'fullscreen',
    'args' => [
        'type' => 'margin',
        'variation' => 'all'
    ],
    'argTypes' => [
        'type' => [
            'control' => 'select',
            'options' => [
                'Margin' => 'margin',
                'Padding' => 'padding',
                'NegativeMargin' => 'negative-margin',
            ]
        ],
        'variation' => [
            'control' => 'select',
            'options' => [
                'All' => 'all',
                'Top' => 'top',
                'Bottom' => 'bottom',
                'Left' => 'left',
                'Right' => 'right',
                'Horizontal' => 'horizontal',
                'Vertical' => 'vertical',
            ]
        ]
    ]
])
<x-docs-page
    title="Spacing"
    description="Documentation for the project's spacing values. Use the story's controls to switch between the different variations"
>
    <x-ui-spacing :type="$type" :variation="$variation" />
</x-docs-page>
