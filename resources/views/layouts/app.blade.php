<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ trans('messages.title') }}</title>

    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body { background-color: #1a1d29; color: #fff; }
        .movie-card { transition: transform 0.2s; background: #2c3142; border: none; }
        .movie-card:hover { transform: scale(1.05); z-index: 10; }
        .navbar { background-color: #0f1118; }
        .loader { display: none; text-align: center; padding: 20px; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand fw-bold text-warning" href="#">ReelHub</a>
            <div class="d-flex align-items-center">
                <a href="{{ route('lang.switch', 'en') }}" class="nav-link text-white mx-2 {{ app()->getLocale() == 'en' ? 'fw-bold text-warning' : '' }}">EN</a>
                <span class="text-white">|</span>
                <a href="{{ route('lang.switch', 'id') }}" class="nav-link text-white mx-2 {{ app()->getLocale() == 'id' ? 'fw-bold text-warning' : '' }}">ID</a>

                @guest
                    <a href="{{ route('login') }}" class="btn btn-outline-light ms-3 btn-sm">{{ trans('messages.login') }}</a>
                    <a href="{{ route('register') }}" class="btn btn-warning ms-2 btn-sm">{{ trans('messages.register') }}</a>
                @else
                    <div class="dropdown ms-3">
                        <button class="btn btn-secondary dropdown-toggle btn-sm" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ Auth::user()->name }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark" aria-labelledby="dropdownMenuButton1">
                            <li><a class="dropdown-item" href="{{ route('favorites.index') }}">{{ trans('messages.my_favorites') }}</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    {{ trans('messages.logout') }}
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </div>
                @endguest
            </div>
        </div>
    </nav>

    <div class="container mt-5 pt-5">
        @yield('content')
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @stack('scripts')
</body>
</html>
