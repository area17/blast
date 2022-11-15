@isset($items)
    <div {{ $attributes->class([
        'blast-grid',
        'blast-grid-cols-1',
        'sm:blast-grid-cols-2',
        'md:blast-grid-cols-3',
        'xl:blast-grid-cols-4',
        'blast-gap-6'
    ]) }}>
        @foreach ($items as $key => $item)
            <div>
                <div
                    class="blast-flex blast-items-center blast-justify-center blast-w-full blast-h-16 md:blast-h-36 blast-mb-2 blast-border-2 blast-border-solid blast-border-gray-300 blast-bg-gray-200"
                    style="{{ $property }}: {{ $item }};"
                >
                </div>

                <div class="
                    blast-inline-block
                    blast-text-sm blast-text-gray-800
                    blast-font-mono
                    blast-break-words
                ">
                    @if ($key === 'DEFAULT')
                        {{ $prefix }}
                    @else
                        {{ $prefix }}-{{ $key }}
                    @endif
                </div>

                <div class="blast-text-sm blast-font-mono blast-text-gray-500 blast-break-words">
                    {{ $item }}
                </div>
            </div>
        @endforeach
    </div>
@endisset
