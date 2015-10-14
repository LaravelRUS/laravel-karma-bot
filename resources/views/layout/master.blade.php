<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8" />
    <title>@section('title') GitterBot @show</title>

    <script>var config = {!! json_encode([
        'csrf' => csrf_token(),
        'user' => Auth::user()
    ]) !!};</script>
    <script src="{{ asset_ts('assets/app.js') }}"></script>
    <link rel="stylesheet" href="{{ asset_ts('assets/app.css') }}" />
</head>
<body>
    @yield('content')
    <script>
        (function(global) {
            global['app'] = (new (require('Application'))).run();
        })(window);
    </script>
</body>
</html>