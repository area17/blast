@isset($items)
    <div {{ $attributes }}>
        @foreach ($items as $name => $properties)

            <div class="blast-pb-1 blast-border-2 blast-border-gray-200 {{ $loop->index > 0 ? 'blast-mt-8' : '' }}">
                <div>
                    <div
                        class="blast-p-4 blast-bg-gray-100 blast-whitespace-nowrap blast-overflow-ellipsis blast-overflow-hidden {{ 'f-'. $name }}"
                    >
                        The quick brown fox jumped over the lazy dog.
                    </div>

                    <div class="
                        blast-p-4
                        blast-mb-2
                        blast-border-b-2 blast-border-gray-200
                        blast-text-sm
                        blast-font-mono
                        blast-break-words
                    ">
                        {{ 'f-'. $name }}
                    </div>

                    <div class="blast-overflow-x-auto">
                        <table class="blast-w-full blast-text-xs blast-font-mono blast-min-w-150 blast-table-fixed">
                            <thead>
                                <tr>
                                    @foreach ($screens as $screen)
                                        <th class="blast-py-2 blast-px-4 blast-font-medium blast-text-left">{{ $screen }}</th>
                                    @endforeach
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($properties as $key => $property)
                                    <tr>
                                        @foreach ($property as $item)
                                            <td class="blast-py-1 blast-px-2" colspan="{{ $item['span'] }}">
                                                <div class="blast-p-2 {!! $bgColor($key) !!}">
                                                    @if($loop->index === 0)
                                                        {{ $key }}:
                                                    @endif

                                                    {{ $item['value'] }}
                                                </div>
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        @endforeach
    </div>
@endisset
