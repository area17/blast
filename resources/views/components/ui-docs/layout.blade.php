<div>
    @if ($type === 'text-list')
        <div class="blast-text-sm blast-font-mono blast-text-gray-500 blast-break-words">
            @foreach ($items as $key => $item)
                <code>{{ $key }}: {{ $item }}</code><br>
            @endforeach
        </div>
    @endif

    @if ($type === 'gutter-inner')
        @foreach ($items as $key => $item)
            <div class="{{ $loop->index > 0 ? 'blast-mt-4 blast-pt-4 blast-border-t' : '' }} blast-border-solid blast-border-gray-200">
                <div class="blast-grid blast-grid-cols-6 blast-bg-blue-500" style="gap: {{ $item }}">
                    @for ($i = 0; $i < 6; $i++)
                        <div class="blast-h-10 blast-bg-gray-200"></div>
                    @endfor
                </div>

                <div class="
                    blast-inline-block
                    blast-text-sm blast-text-gray-800
                    blast-font-mono
                    blast-break-words
                ">
                    {{ $key }}
                </div>

                <div class="blast-text-sm blast-font-mono blast-text-gray-500 blast-break-words">
                    {{ $item }}
                </div>
            </div>
        @endforeach
    @endif

    @if ($type === 'gutter-outer')
        @foreach ($items as $key => $item)
            <div class="{{ $loop->index > 0 ? 'blast-mt-4 blast-pt-4 blast-border-t' : '' }} blast-border-solid blast-border-gray-200">
                <div class="blast-bg-blue-500" style="padding: 0 {{ $item }}">
                    <div class="blast-h-10 blast-bg-gray-200"></div>
                </div>

                <div class="
                    blast-inline-block
                    blast-text-sm blast-text-gray-800
                    blast-font-mono
                    blast-break-words
                ">
                    {{ $key }}
                </div>

                <div class="blast-text-sm blast-font-mono blast-text-gray-500 blast-break-words">
                    {{ $item }}
                </div>
            </div>
        @endforeach
    @endif
</div>
