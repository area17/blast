<div class="blast:min-h-screen blast:bg-white">
    <div class="blast:bg-blue-500 blast:text-white">
        <div class="blast:container blast:flex blast:flex-col blast:pt-6 blast:pb-9 blast:md:pt-8 blast:md:pb-12">
            @if($label)
                <p class="blast:text-sm blast:antialiased">
                    {{ $label }}
                </p>
            @endif

            <div class="dev-page__hero-bottom">
                @if($title)
                    <h1 class="blast:mt-8 blast:md:mt-10 blast:text-6xl blast:md:text-7xl blast:font-semibold blast:antialiased">
                        {{ $title }}
                    </h1>
                @endif

                @if($description)
                    <div class="blast:md:w-10/12 blast:mt-4 blast:md:mt-5  blast-wysiwyg">
                        {!! \Illuminate\Support\Str::markdown($description) !!}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class=" blast:container blast:mt-6 blast:pb-12">
        {{ $slot }}
    </div>
</div>
