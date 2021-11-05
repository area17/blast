<div>
    @foreach ($items as $key => $item)
        <div class="{{ $loop->index > 0 ? 'blast-mt-4 blast-pt-4 blast-border-t' : '' }} blast-border-solid blast-border-gray-200">
            <div class="blast-w-full blast-h-10 blast-bg-blue-500" style="{{ $property ?? 'width' }}: {{ $item }}">
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
