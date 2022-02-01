<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
@yield('add_scripts')

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('css/favicon_io/favicon-32x32.png') }}" type="image/png" />
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" />

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    @livewireStyles
@yield('add_styles')
</head>
<body>
    <div id="app">
        <div class="container">
            <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            
                <a class="navbar-brand" href="{{ url('/') }}">
                    @if (!get_custom_logo())
                    <img src="{{ asset('css/img/annie_h_logo_sm.png') }}" class="pull-left" />
                    @else
                    <img src="{{ asset(get_custom_logo()) }}" class="plull-left" />
                    @endif
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">
@if (Auth::user())
                        <li class="nav-item {{ request()->is('dashboard') ? 'active' : '' }}">
                            <a class="nav-link" href="/">{{ __('app.home') }} <span class="sr-only">(current)</span></a>
                        </li>
                        <li class="nav-item @if ( request()->is('dashboard/rundown/*') || request()->is('dashboard/rundown')) active @endif">
                          <a class="nav-link" href="/dashboard/rundown">{{ __('app.scripts') }}</a>
                        </li>
                        <li class="nav-item {{ request()->is('dashboard/templates') ? 'active' : '' }}">
                            <a class="nav-link" href="/dashboard/templates">{{ __('app.templates') }}</a>
                        </li>
    @if (Auth::user()->admin)
                        <li class="nav-item dropdown @if ( request()->is('dashboard/settings/*') || request()->is('dashboard/settings')) active @endif">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-expanded="false">Admin</a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="nav-link" href="/dashboard/settings">{{ __('app.settings') }}</a>
                                <a class="nav-link" href="/dashboard/settings/users">{{ __('app.users') }}</a>
                                <div class="dropdown-divider"></div>
                                <a class="nav-link" href="/dashboard/videohub">{{ __('app.videohub') }}</a>
                            </div>
                        </li>
    @endif
@endif
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif
                            @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="@if (Auth::user()->cas){{ route('cas.logout') }}@else{{ route('logout') }}@endif"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>
                                    @if (Auth::user()->cas)
                                    <form id="logout-form" action="{{ route('cas.logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                    @else
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                    @endif
                                    <form action="{{ route('app.setlang') }}" method="POST" id="setLangForm">
                                        @csrf
                                        <input type="hidden" name="locale" value="en">
                                    </form>
                                    <a class="flag ml-4" href="#" onclick="setLanguage('en');">!</a><a class="flag ml-1" href="#" onclick="setLanguage('sv');">w</a>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </nav>
        </div>
        <main class="py-4">
            @yield('content')
        </main>
    </div>
    @livewireScripts
    <script>
        function setLanguage(locale){
            $('#setLangForm input[name=locale]').val(locale);
            $('#setLangForm').submit();
        }
</script>
    @yield('footer_scripts')
</body>
</html>
