<div>
    <div class="blast-bg-blue-500 blast-text-white">
        <div class="blast-container blast-flex blast-flex-col blast-pt-6 blast-pb-9 md:blast-pt-8 md:blast-pb-12">
            @if($label)
                <p class="blast-text-sm blast-antialiased">
                    {{ $label }}
                </p>
            @endif

            <div class="dev-page__hero-bottom">
                @if($title)
                    <h1 class="blast-mt-8 md:blast-mt-10 blast-text-6xl md:blast-text-7xl blast-font-semibold blast-antialiased">
                        {{ $title }}
                    </h1>
                @endif

                @if($description)
                    <div class="md:blast-w-10/12 blast-mt-4 md:blast-mt-5  blast-wysiwyg">
                        {!! Str::markdown($description) !!}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class=" blast-container blast-mt-6 blast-mb-12">
        {{ $slot }}
    </div>
</div>
