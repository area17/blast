@storybook([
    'name' => 'Link',
    'status' => 'readyForQA',
    'design' => "https://www.figma.com/file/LKQ4FJ4bTnCSjedbRpk931/Sample-File",
    'args' => [
        'href' => 'https://example.com',
        'text' => 'Hello World',
    ],
])

<x-link.link :href="$href">
    {{ $text }}
</x-link.link>
