<!doctype html>
<html lang="de">
<head>
    <title>Schokoladen @yield('title')</title>
    <meta charset="utf-8">
    {!! Feed::link(url('feed'), 'rss', 'Schokoladen Feed') !!}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="robots" content="index, follow" />
    <meta http-equiv="expires" content="3600" />
    <meta http-equiv="cache-control" content="max-age=3600">
    <link rel="icon" href="/favicons/favicon.ico" type="image/x-icon">
    <link rel="icon" type="image/png" href="/favicons/favicon-16x16.png" sizes="16x16">
    <link rel="icon" type="image/png" href="/favicons/favicon-32x32.png" sizes="32x32">
    <link rel="icon" type="image/png" href="/favicons/favicon-96x96.png" sizes="96x96">
    <meta name="description" content="Schokoladen-Mitte Berlin" />
    <meta name="keywords" content="Schokoladen,Berlin,Musik,Musik Cafe,Kneipe,Kultur,Szene,Subkultur,Konzerte,Livemusik,live music,Veranstaltungs-Kneipe,Veranstaltungen,Lesung,alternativ" />
    <meta http-equiv="imagetoolbar" content="no" />
    <link rel="preload" as="image" href="{{ asset('img/power/batcow_bg_transp.png') }}">
    <link rel="preload" as="image" href="{{ asset('img/power/the_dark_art.png') }}">
    <link rel="preload" as="font">
    <link type="text/css" rel="stylesheet" href="{{ mix('css/dark.css') }}?{{ time() }}" />
    <script src="{{ mix('js/app.js') }}?{{ time() }}" type="text/javascript" charset="utf-8"></script>
    @yield('extra-headers')
</head>
<body>

@if(env('BOOTSTRAP_DEBUG'))
    @include('debug.bootstrap.display')
@endif

<div class="container col-12">
    <div class="header">
        @section('header')
            @include('public.templates.topNavigation')
        @show
        @yield('header-content')
    </div>

    <div class="main row">
        <div id="content" class="content col-12 mb-5">
            @yield('sidebarLeft')
            @yield('content')
            @yield('sidebarRight')
        </div>
    </div>

    <div class="footer row">
        @section('bottom-navigation')
            @include('public.templates.bottomNavigation')
        @show
        @yield('footer-content')
    </div>
</div>
<div class="background_right h-100">
    <img class="h-100 img-responsive" src="{{ asset('img/power/the_dark_art.png') }}" width="66px" height="718px" alt="Darkside" title="Darlside">
</div>
@yield('inline-scripts')

@if(config('piwik.url'))
    @include('public.analytic.piwik')
@endif
</body>
</html>
