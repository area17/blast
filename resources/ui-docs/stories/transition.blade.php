@storybook([
    'layout' => 'fullscreen',
    'preset' => 'ui-docs.transitions'
])
<x-docs-page
    title="Transition"
    description="Documentation for the project's transition values."
>
    <x-ui-transition :delay="$delay ?? null" :duration="$duration ?? null" />
</x-docs-page>
