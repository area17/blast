@isset($colors)
    <div {{ $attributes }}>
        <table class="blast-w-full">
            <thead>
                <tr>
                    <td class="blast-py-4 blast-pr-2 blast-font-medium">Example</td>
                    <td class="blast-py-4 blast-px-2 blast-font-medium">Name</td>
                    <td class="blast-py-4 blast-pl-2 blast-font-medium">Value</td>
                </tr>
            </thead>
            <tbody>
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

                    <tr>
                        <td class="blast-w-10 md:blast-w-40 blast-py-4 blast-pr-2 blast-border-t blast-border-gray-200">
                            <div
                                class="blast-flex blast-items-center blast-justify-center blast-w-full blast-h-10 blast-border-2 blast-border-solid {{ $item['type'] === 'text' ? 'blast-border-gray-200' : 'blast-border-transparent' }}"
                                style="{{ $style }}: {{ $item['color'] }};"
                            >
                                @if($item['type'] === 'text')
                                    <span>Aa</span>
                                @endif
                            </div>
                        </td>
                        <td class="blast-py-4 blast-px-2 blast-border-t blast-border-gray-200">
                            <div class="
                                blast-inline-block
                                blast-text-sm
                                blast-font-mono
                                blast-break-words
                            ">
                                {{ $classname }}
                            </div>
                        </td>
                        <td class="blast-py-4 blast-pl-2 blast-border-t blast-border-gray-200">
                            <div class="blast-text-sm blast-font-mono blast-text-gray-500 blast-break-words">
                                {{ $item['color'] }}
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endisset
