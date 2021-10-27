<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
@yield('add_scripts')

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

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
                    <img src="{{ asset('css/img/annie_h_logo_sm.png') }}" alt="HDa" class="pull-left">
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">
                @if (Auth::user())
                        <li class="nav-item active">
                            <a class="nav-link" href="/">{{ __('app.home') }} <span class="sr-only">(current)</span></a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" href="/dashboard/rundown">{{ __('app.scripts') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/dashboard/templates">{{ __('app.templates') }}</a>
                        </li>
                @if (Auth::user()->admin)
                        <li class="nav-item dropdown">
                          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            {{ __('app.admin') }}
                          </a>
                          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            {{-- <a class="dropdown-item" href="/dashboard/settings/hardware">Hårdvaruinställningar</a> --}}
                            <a class="dropdown-item" href="/dashboard/settings/roles">Roller</a>
                            <a class="dropdown-item" href="/dashboard/settings/users">Användare</a>
                            <a class="dropdown-item" href="/dashboard/settings/videohub">Videohubinställningar</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#">Information</a>
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
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </nav>
        </div>
        <main class="py-4">
@if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
@if (session('status'))
	<div class="alert alert-success">
		{{ session('status') }}
	@if (session('link'))
		<a href="/dashboard/rundown/xml/{{ session('link') }}">{{ __('rundown.message_ccgfile') }}</a>
	@endif
	</div>
@endif
            @yield('content')
        </main>
    </div>
    @livewireScripts
    @yield('footer_scripts')
</body>
</html>
