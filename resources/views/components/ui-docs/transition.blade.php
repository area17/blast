<div>
    <div class="blast-flex">
        @isset($duration)
            <div class="
                blast-text-sm blast-text-gray-800
                blast-font-mono
                blast-break-words
            ">
                duration-{{ \Illuminate\Support\Str::remove(['ms', 's'], $duration) }}: {{ $duration }};
            </div>
        @endisset

        @isset($delay)
            <div class="
                blast-ml-6
                blast-text-sm blast-text-gray-800
                blast-font-mono
                blast-break-words
            ">
                delay-{{ \Illuminate\Support\Str::remove(['ms', 's'], $delay) }}: {{ $delay }};
            </div>
        @endisset
    </div>

    @foreach ($items as $key => $item)
        <div class="blast-mt-4 blast-pt-4 blast-border-t blast-border-solid blast-border-gray-200">
            <div class="blast-w-full blast-bg-gray-200 blast-group">
                <div
                    class="blast-w-16 blast-h-16 blast-bg-blue-500 blast-transition-all group-hover:blast-w-full"
                    style="
                        transition-timing-function: {{ $item }};
                        transition-delay: {{ $delay }};
                        transition-duration: {{ $duration }};
                    ">
                </div>
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
