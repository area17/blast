@php
    $canvasBgColor = config('blast.canvas_bg_color') ?? null;
    $css = config('blast.assets.css') ?? [];
    $js = config('blast.assets.js') ?? [];
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>

    @if (!empty($css))
        @foreach ($css as $key => $asset)
            @if (is_string($key))
                @if($key == $assetGroup)
                    <link rel="stylesheet" href="{{ $asset }}">
                @endif
            @else
                <link rel="stylesheet" href="{{ $asset }}">
            @endif
        @endforeach
    @endif

    @if ($canvasBgColor)
        <style>
            .sb-show-main {
                background-color: {{ $canvasBgColor }}
            }
        </style>
    @endif
</head>

<body>
    @include('stories.'. $component)

    @if (!empty($js))
        @foreach ($js as $asset)
            <script src="{{ $asset }}"></script>
        @endforeach
    @endif
</body>
</html>
