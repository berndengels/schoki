<!doctype html>
<html>
<head>
    <title>Schokoladen @yield('title')</title>
    <meta charset="utf-8">
    {!! Feed::link(url('feed'), 'rss', 'Schokoladen Feed') !!}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="robots" content="index, follow" />
    <meta http-equiv="pragma" content="no-cache" />
    <meta http-equiv="expires" content="0" />
    <meta name="description" content="Schokoladen-Mitte Berlin" />
    <meta name="keywords" content="Schokoladen,Berlin,Musik,Musik Cafe,Kneipe,Kultur,Szene,Subkultur,Konzerte,Livemusik,live music,Veranstaltungs-Kneipe,Veranstaltungen,Lesung,alternativ" />
    <meta http-equiv="imagetoolbar" content="no" />
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">
    <link href="https://unpkg.com/ionicons@4.2.2/dist/css/ionicons.min.css" rel="stylesheet">
    <link type="text/css" rel="stylesheet" href="{{ asset('css/dark.css') }}" />
    <script src="{{ asset('js/app.js') }}" type="text/javascript" charset="utf-8"></script>
    <script src="https://unpkg.com/ionicons@4.2.2/dist/ionicons.js"></script>
    @yield('extra-headers')
</head>
<body>

@if(env('BOOTSTRAP_DEBUG'))
    @include('debug.bootstrap.display')
@endif;

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
