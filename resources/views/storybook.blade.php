<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>

    @if (!empty($css))
        @foreach ($css as $key => $asset)
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
        @foreach ($js as $key => $asset)
            <script src="{{ $asset }}"></script>
        @endforeach
    @endif
</body>
</html>
