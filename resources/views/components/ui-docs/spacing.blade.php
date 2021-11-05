<div>
    @foreach ($spacing as $key => $item)
        <div class="{{ $loop->index > 0 ? 'blast-mt-4 blast-pt-4 blast-border-t' : '' }} blast-border-solid blast-border-gray-200">
            <div class=" blast-h-10 blast-bg-blue-500" style="width: {{ $item }}">
            </div>

            <div class="
                blast-inline-block
                blast-text-sm blast-text-gray-800
                blast-font-mono
                blast-break-words
            ">
                {{ $prefix }}-{{ $key }}
            </div>

            <div class="blast-text-sm blast-font-mono blast-text-gray-500 blast-break-words">
                {{ $item }}
            </div>
        </div>
    @endforeach
</div>
