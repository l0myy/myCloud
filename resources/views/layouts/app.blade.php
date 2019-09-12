<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
<main role="main" class="container">

    @if(Auth::check())
        @if(Session::has('message'))
            <div class="alert-success">
                {{Session::get('message')}}
            </div>
        @endif
        @if (Session::has('error'))
            <div class="alert-danger">
                {{Session::get('error')}}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="container" id="my-logout">
            <div class="row justify-content-end">
                <a href="#" onclick="document.getElementById('logout-form').submit();">
                    <button type="submit" class="btn btn-outline-secondary">Logout</button>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
            </div>
        </div>
        @yield('dirsInfo')
        @yield('filesInfo')
        @yield('modalWindows')
        <div class="form-group col-md-4">
            @else
                @yield('content')
        </div>
    @endif
</main>
</body>
</html>
