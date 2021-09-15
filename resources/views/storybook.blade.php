@php
    $canvasBgColor = config('blast.canvas_bg_color') ?? null;
    $css = config('blast.assets.css') ?? [];
    $js = config('blast.assets.js') ?? [];
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta name="viewport" content="width=device-width,initial-scale=1">

    <link rel="stylesheet" href="{{ config('app.url') . '/blast/main.css' }}">

    @if (!empty($css))
        @foreach ($css as $asset)
            <link rel="stylesheet" href="{{ $asset }}">
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
