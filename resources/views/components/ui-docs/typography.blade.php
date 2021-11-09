@isset($items)
    <div {{ $attributes }}>
        @foreach ($items as $key => $item)
            <div class="{{ $loop->index > 0 ? 'blast-mt-4 blast-pt-4 blast-border-t' : '' }} blast-border-solid blast-border-gray-200">
                <div
                    class="blast-mb-2 blast-p-2 blast-border-2 blast-border-solid blast-border-gray-200 blast-bg-gray-100 blast-whitespace-nowrap blast-overflow-ellipsis blast-overflow-hidden"
                    style="{{ $property }}: {{ $item }};"
                >
                    The quick brown fox jumped over the lazy dog.

                    @if($property === 'line-height')
                        <br>The quick brown fox jumped over the lazy dog.
                    @endif
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
