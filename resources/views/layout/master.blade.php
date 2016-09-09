<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8" />
    <title>@section('title') Laravel Karma @show</title>
    <script>var config = {!! json_encode([
        'csrf' => csrf_token(),
        'user' => Auth::user()
    ]) !!};</script>
    <script src="{{ asset_ts('assets/app.js', true) }}"></script>
    <link rel="stylesheet" href="{{ asset_ts('assets/app.css', true) }}" />

    <link rel="apple-touch-icon"         href="{{ asset_ts('favicon.png', true) }}" />
    <link rel="icon" type="image/x-icon" href="{{ asset_ts('favicon.ico', true) }}" />
    <link rel="icon" type="image/png"    href="{{ asset_ts('favicon.png', true) }}" />
</head>
<body>
    @yield('content')
    <script>
        (function(global) {
            global['app'] = (new (require('/Application').default)).run();
        })(window);
    </script>
</body>
</html>