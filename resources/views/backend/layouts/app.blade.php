<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
{{--    <script src="{{ asset('backend/js/app.js') }}" defer></script>--}}

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    {{-- @routes() --}}


    <!-- Styles -->
    @yield('css')
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link href="{{ asset('backend/css/app.css') }}" rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel='stylesheet'
          type='text/css'>
    <link href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700" rel='stylesheet' type='text/css'>

    <style>
        ul {
            display: flex;
            margin-bottom: 0px;
        }

        ul li {
            list-style: none;
            margin-right: 5px;
        }
    </style>

    @yield('css')
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ route('backend.home') }}">
                    Home
                </a>

                <ul>
                    <li><a href="{{route('backend.admin.index')}}" class="btn ">admin</a></li>
                    <li><a href="{{route('backend.channel.index')}}" class="btn ">channel</a></li>
                    <li><a href="{{route('backend.movie.index')}}" class="btn ">movie</a></li>
                    <li><a href="{{route('backend.option.index')}}" class="btn ">option</a></li>
                    <li><a href="{{route('backend.permission.index')}}" class="btn ">permission</a></li>
                    <li><a href="{{route('backend.playlist.index')}}" class="btn ">playlist</a></li>
                    <li><a href="{{route('backend.role.index')}}" class="btn ">role</a></li>
                    <li><a href="{{route('backend.tag.index')}}" class="btn ">tag</a></li>
                    <li><a href="{{route('backend.type.index')}}" class="btn ">type</a></li>
                    <li><a href="{{route('backend.video.index')}}" class="btn ">video</a></li>
                </ul>
{{-- 
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('backend.login') }}">{{ __('Login') }}</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('backend.register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else

                                <a  class="nav-link " href="#"  >
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div>
                                    <a class="dropdown-item" href="{{ route('backend.logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();" style="margin-top: 4px">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('backend.logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                        @endguest
                    </ul>
                </div> --}}
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
    @yield('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>

</html>
