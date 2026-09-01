<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>{{ config('app.name', 'Pterodactyl') }} - @yield('title')</title>
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <meta name="_token" content="{{ csrf_token() }}">

        <link rel="apple-touch-icon" sizes="180x180" href="/favicons/apple-touch-icon.png">
        <link rel="icon" type="image/png" href="/favicons/favicon-32x32.png" sizes="32x32">
        <link rel="icon" type="image/png" href="/favicons/favicon-16x16.png" sizes="16x16">
        <link rel="manifest" href="/favicons/manifest.json">
        <link rel="mask-icon" href="/favicons/safari-pinned-tab.svg" color="#0f62fe">
        <link rel="shortcut icon" href="/favicons/favicon.ico">
        <meta name="msapplication-config" content="/favicons/browserconfig.xml">
        <meta name="theme-color" content="#0f62fe">

        @include('layouts.scripts')

        @section('scripts')
            {!! Theme::css('vendor/select2/select2.min.css?t={cache-version}') !!}
            {!! Theme::css('vendor/sweetalert/sweetalert.min.css?t={cache-version}') !!}
            {!! Theme::css('vendor/carbon/styles.min.css?t={cache-version}') !!}
            {!! Theme::css('css/carbon-admin.css?t={cache-version}') !!}
            {!! Theme::css('css/pterodactyl.css?t={cache-version}') !!}
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        @show
    </head>
    <body class="cds--g10 ptero-admin">
        <a class="cds--skip-to-content" href="#main-content">Skip to main content</a>
        <header class="cds--header" aria-label="{{ config('app.name', 'Pterodactyl') }}">
            <button class="cds--header__menu-trigger cds--header__action" type="button" id="ptero-side-nav-toggle" aria-label="Open menu">
                <svg focusable="false" preserveAspectRatio="xMidYMid meet" xmlns="http://www.w3.org/2000/svg" fill="currentColor" width="20" height="20" viewBox="0 0 32 32" aria-hidden="true"><path d="M4 6H28V8H4zM4 24H28V26H4zM4 15H28V17H4z"></path></svg>
            </button>
            <a class="cds--header__name" href="{{ route('index') }}">
                <span class="cds--header__name--prefix">Pterodactyl</span>
                &nbsp;{{ config('app.name', 'Pterodactyl') }}
            </a>
            <div class="cds--header__global">
                <a class="cds--header__action" href="{{ route('account') }}" title="{{ Auth::user()->name_first }} {{ Auth::user()->name_last }}">
                    <img src="https://www.gravatar.com/avatar/{{ md5(strtolower(Auth::user()->email)) }}?s=64" alt="" width="20" height="20" style="border-radius: 50%;">
                </a>
                <a class="cds--header__action" href="{{ route('index') }}" title="Exit Admin Control">
                    <i class="fa fa-server" aria-hidden="true"></i>
                </a>
                <a class="cds--header__action" href="{{ route('auth.logout') }}" id="logoutButton" title="Logout">
                    <i class="fa fa-sign-out" aria-hidden="true"></i>
                </a>
            </div>
        </header>
        <div class="cds--side-nav__overlay" id="ptero-side-nav-overlay"></div>
        <nav class="cds--side-nav cds--side-nav--ux cds--side-nav--expanded" aria-label="Admin" id="ptero-side-nav">
            <ul class="cds--side-nav__items">
                <li class="cds--side-nav__item"><span class="ptero-side-nav-label">Basic administration</span></li>
                <li class="cds--side-nav__item">
                    <a class="cds--side-nav__link {{ Route::currentRouteName() !== 'admin.index' ?: 'cds--side-nav__link--current' }}" href="{{ route('admin.index') }}">
                        <span class="cds--side-nav__link-text">Overview</span>
                    </a>
                </li>
                <li class="cds--side-nav__item">
                    <a class="cds--side-nav__link {{ ! starts_with(Route::currentRouteName(), 'admin.settings') ?: 'cds--side-nav__link--current' }}" href="{{ route('admin.settings') }}">
                        <span class="cds--side-nav__link-text">Settings</span>
                    </a>
                </li>
                <li class="cds--side-nav__item">
                    <a class="cds--side-nav__link {{ ! starts_with(Route::currentRouteName(), 'admin.api') ?: 'cds--side-nav__link--current' }}" href="{{ route('admin.api.index') }}">
                        <span class="cds--side-nav__link-text">Application API</span>
                    </a>
                </li>
                <li class="cds--side-nav__item"><span class="ptero-side-nav-label">Management</span></li>
                <li class="cds--side-nav__item">
                    <a class="cds--side-nav__link {{ ! starts_with(Route::currentRouteName(), 'admin.databases') ?: 'cds--side-nav__link--current' }}" href="{{ route('admin.databases') }}">
                        <span class="cds--side-nav__link-text">Databases</span>
                    </a>
                </li>
                <li class="cds--side-nav__item">
                    <a class="cds--side-nav__link {{ ! starts_with(Route::currentRouteName(), 'admin.locations') ?: 'cds--side-nav__link--current' }}" href="{{ route('admin.locations') }}">
                        <span class="cds--side-nav__link-text">Locations</span>
                    </a>
                </li>
                <li class="cds--side-nav__item">
                    <a class="cds--side-nav__link {{ ! starts_with(Route::currentRouteName(), 'admin.nodes') ?: 'cds--side-nav__link--current' }}" href="{{ route('admin.nodes') }}">
                        <span class="cds--side-nav__link-text">Nodes</span>
                    </a>
                </li>
                <li class="cds--side-nav__item">
                    <a class="cds--side-nav__link {{ ! starts_with(Route::currentRouteName(), 'admin.servers') ?: 'cds--side-nav__link--current' }}" href="{{ route('admin.servers') }}">
                        <span class="cds--side-nav__link-text">Servers</span>
                    </a>
                </li>
                <li class="cds--side-nav__item">
                    <a class="cds--side-nav__link {{ ! starts_with(Route::currentRouteName(), 'admin.users') ?: 'cds--side-nav__link--current' }}" href="{{ route('admin.users') }}">
                        <span class="cds--side-nav__link-text">Users</span>
                    </a>
                </li>
                <li class="cds--side-nav__item"><span class="ptero-side-nav-label">Service management</span></li>
                <li class="cds--side-nav__item">
                    <a class="cds--side-nav__link {{ ! starts_with(Route::currentRouteName(), 'admin.mounts') ?: 'cds--side-nav__link--current' }}" href="{{ route('admin.mounts') }}">
                        <span class="cds--side-nav__link-text">Mounts</span>
                    </a>
                </li>
                <li class="cds--side-nav__item">
                    <a class="cds--side-nav__link {{ ! starts_with(Route::currentRouteName(), 'admin.nests') ?: 'cds--side-nav__link--current' }}" href="{{ route('admin.nests') }}">
                        <span class="cds--side-nav__link-text">Nests</span>
                    </a>
                </li>
            </ul>
        </nav>
        <main class="cds--content" id="main-content">
            <div class="ptero-page-header">
                @yield('content-header')
            </div>
            @if (count($errors) > 0)
                <div class="cds--inline-notification cds--inline-notification--error" role="alert">
                    <div class="cds--inline-notification__text-wrapper">
                        <div class="cds--inline-notification__title">There was an error validating the data provided.</div>
                        <div class="cds--inline-notification__subtitle">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif
            @foreach (Alert::getMessages() as $type => $messages)
                @foreach ($messages as $message)
                    <div class="cds--inline-notification cds--inline-notification--{{ $type === 'danger' ? 'error' : ($type === 'success' ? 'success' : ($type === 'warning' ? 'warning' : 'info')) }}" role="alert">
                        <div class="cds--inline-notification__text-wrapper">
                            <div class="cds--inline-notification__subtitle">{{ $message }}</div>
                        </div>
                    </div>
                @endforeach
            @endforeach
            @yield('content')
            <footer class="ptero-admin-footer">
                <span>Copyright &copy; 2015 - {{ date('Y') }} <a href="https://pterodactyl.io/">Pterodactyl Software</a>.</span>
                <span>{{ $appVersion }} · {{ round(microtime(true) - LARAVEL_START, 3) }}s</span>
            </footer>
        </main>
        @section('footer-scripts')
            <script src="/js/keyboard.polyfill.js" type="application/javascript"></script>
            <script>keyboardeventKeyPolyfill.polyfill();</script>

            {!! Theme::js('vendor/jquery/jquery.min.js?t={cache-version}') !!}
            {!! Theme::js('vendor/sweetalert/sweetalert.min.js?t={cache-version}') !!}
            {!! Theme::js('vendor/bootstrap/bootstrap.min.js?t={cache-version}') !!}
            {!! Theme::js('vendor/bootstrap-notify/bootstrap-notify.min.js?t={cache-version}') !!}
            {!! Theme::js('vendor/select2/select2.full.min.js?t={cache-version}') !!}
            {!! Theme::js('js/admin/functions.js?t={cache-version}') !!}
            <script src="/js/autocomplete.js" type="application/javascript"></script>

            <script>
                (function () {
                    var nav = document.getElementById('ptero-side-nav');
                    var overlay = document.getElementById('ptero-side-nav-overlay');
                    var toggle = document.getElementById('ptero-side-nav-toggle');
                    function closeNav() {
                        nav.classList.remove('cds--side-nav--expanded');
                        overlay.classList.remove('cds--side-nav__overlay-active');
                    }
                    function openNav() {
                        nav.classList.add('cds--side-nav--expanded');
                        overlay.classList.add('cds--side-nav__overlay-active');
                    }
                    toggle.addEventListener('click', function () {
                        if (nav.classList.contains('cds--side-nav--expanded') && window.innerWidth < 1056) {
                            closeNav();
                        } else {
                            openNav();
                        }
                    });
                    overlay.addEventListener('click', closeNav);
                })();
            </script>

            @if(Auth::user()->root_admin)
                <script>
                    $('#logoutButton').on('click', function (event) {
                        event.preventDefault();
                        swal({
                            title: 'Do you want to log out?',
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#da1e28',
                            cancelButtonColor: '#393939',
                            confirmButtonText: 'Log out'
                        }, function () {
                             $.ajax({
                                type: 'POST',
                                url: '{{ route('auth.logout') }}',
                                data: {
                                    _token: '{{ csrf_token() }}'
                                },complete: function () {
                                    window.location.href = '{{route('auth.login')}}';
                                }
                        });
                    });
                });
                </script>
            @endif

            <script>
                $(function () {
                    if ($.fn.tooltip) {
                        $('[data-toggle="tooltip"]').tooltip();
                    }
                })
            </script>
        @show
    </body>
</html>
