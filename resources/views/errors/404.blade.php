<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }} - 404</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('css/favicon_io/favicon-32x32.png') }}" type="image/png" />
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" />

    <link href="{{ asset('css/404.css') }}" rel="stylesheet">
</head>
<body>
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            @if (!get_custom_logo())
            <img src="{{ asset('css/img/annie_h_logo_sm.png') }}" class="pull-left" />
            @else
            <img src="{{ asset(get_custom_logo()) }}" class="plull-left" />
            @endif
        </a>
        <div id="art">
            <div id="message">
                <h3>NO SIGNAL</h3>
                <h1>404</h1>
                <a href="{{ url()->previous(); }}" role="button" class="btn btn-custom">{{ __('app.goback') }}</a>
            </div>
        </div>
    </div>
</body>
</html>