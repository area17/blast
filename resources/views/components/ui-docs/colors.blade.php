@isset($colors)
    <div {{ $attributes->class(['blast-grid', 'blast-grid-cols-3', 'blast-gap-6']) }}>
        @foreach ($colors as $classname => $item)
            @php
                switch ($item['type']) {
                    case 'border':
                        $style = 'border-color';
                        break;

                    case 'text':
                        $style = 'color';
                        break;

                    default:
                        $style = 'background-color';
                        break;
                }
            @endphp

            <div>
                <div
                    class="blast-flex blast-items-center blast-justify-center blast-w-full blast-h-16 md:blast-h-36 blast-mb-2 blast-border-2 blast-border-solid {{ $item['type'] === 'text' ? 'blast-border-gray-200' : 'blast-border-transparent' }}"
                    style="{{ $style }}: {{ $item['color'] }};"
                >
                    @if($item['type'] === 'text')
                        <span>Aa</span>
                    @endif
                </div>

                <div class="
                    blast-inline-block
                    blast-text-sm blast-text-gray-800
                    blast-font-mono
                    blast-break-words
                ">
                    {{ $classname }}
                </div>

                <div class="blast-text-sm blast-font-mono blast-text-gray-500 blast-break-words">
                    {{ $item['color'] }}
                </div>
            </div>
        @endforeach
    </div>
@endisset
