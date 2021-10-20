@storybook([
    'name' => 'Paragraph',
    'status' => 'readyForQA',
    'design' => "https://www.figma.com/file/LKQ4FJ4bTnCSjedbRpk931/Sample-File",
    'args' => [
        'text' => 'Hello World',
    ],
])

<x-paragraph.paragraph>
    {{ $text }}
</x-paragraph.paragraph>
