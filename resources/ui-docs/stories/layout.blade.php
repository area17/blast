@storybook([
    'layout' => 'fullscreen',
])
<x-docs-page
    title="Layout"
    description="Documentation for the project's grid layout"
>
    <h2 class="blast-mt-8 md:blast-mt-10 blast-mb-4 blast-border-b blast-border-solid blast-border-gray-200 blast-text-2xl md:blast-text-3xl blast-font-semibold blast-antialiased">Breakpoints</h2>

    <x-ui-breakpoints />

    <h2 class="blast-mt-8 md:blast-mt-10 blast-mb-4 blast-border-b blast-border-solid blast-border-gray-200 blast-text-2xl md:blast-text-3xl blast-font-semibold blast-antialiased">Column Counts</h2>

    <x-ui-columns />

    <h2 class="blast-mt-8 md:blast-mt-10 blast-mb-4 blast-border-b blast-border-solid blast-border-gray-200 blast-text-2xl md:blast-text-3xl blast-font-semibold blast-antialiased">Container Widths</h2>

    <x-ui-container />

    <h2 class="blast-mt-8 md:blast-mt-10 blast-mb-4 blast-border-b blast-border-solid blast-border-gray-200 blast-text-2xl md:blast-text-3xl blast-font-semibold blast-antialiased">Inner Gutters</h2>

    <x-ui-gutter-inner />

    <h2 class="blast-mt-8 md:blast-mt-10 blast-mb-4 blast-border-b blast-border-solid blast-border-gray-200 blast-text-2xl md:blast-text-3xl blast-font-semibold blast-antialiased">Outer Gutters</h2>

    <x-ui-gutter-outer />
</x-docs-page>
