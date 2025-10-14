<!DOCTYPE html>
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
















/* BACKUP CODE AUS: event-view.blade */

<div class="collapseToggle mbs d-none">
    <div class="eventHeader col-12" style="display: flex; flex-direction: row; align-items: center;">
        <div class="subHeader m-0 p-0 pl-2" style="display: flex; flex-direction: column; text-align: center; width: 15%; font-size: 3rem; font-weight: bold; line-height: 1.2;">
            <span class="">{{ __($eventDate->format('l')) }}</span>
            <span class="ml-1">{{ $eventDate->format('d.m.') }}</span>
            <span class="ml-1">{{ $item->getEventTime() }} Uhr</span>

            @if($item->getCategory())
            <span class="category ml-1">
                @if($item->getCategory()->icon)
                    <ion-icon name="{{ $item->getCategory()->icon }}"
                              title="{{ $item->getCategory()->name }}"></ion-icon>
                @endif
                {{ $item->getCategory()->name }}
            </span>
            @endif

            @if ($item->getTheme())
            <span class="promoter p-0 m-0">
                <h7>{{ $item->getTheme()->name }}</h7>
            </span>
            @endif
        </div>

        <div class="title mt-3 mb-2 p-0 pl-2">
            <div class="p-0 h3 font-weight-bold">
                {{ $item->getTitle() }}
            </div>
        </div>

        @if($item->getTicketlink())
        <a class="ticketlink"
           role="button"
           title="zum Ticket Shop"
           target="_blank"
           href="{{ $item->getTicketlink() }}">
            <ion-icon name="ticket"></ion-icon>
            <span class="d-none d-md-inline-block">Tickets</span></a>
        @endif
        <button class="btn-toggle off"
                role="button"
                data-toggle="collapse"
                aria-expanded="false"
                data-target="#{{ $domID }}"
                aria-controls="{{ $domID }}">open
        </button>

        <div class="header-buttons d-inline-block clearfix m-0 p-0 pr-2"></div>
    </div>
    <div class="preview_img">
        @if ($item->getImages()->count() > 0 )
        @php
        /**
        * @var $img Images
        */
        $img = $item->getImages()->first()
        @endphp
        <img src="/media/images/{{ $img->internal_filename }}"
             class="mx-auto"
             width="{{ $img->displayWidth }}"
             height="{{ $img->displayHeight }}"
             title="{{ $img->title ?? 'Event Bild' }}"
             alt="{{ $img->title ?? 'Event Bild' }}"
        >
        @endif
    </div>
</div>

<div id="{{ $domID }}" data-event-date="{{ $item->getEventDate() }}" class="eventBody collapse col-12 mt-4 pt-0">
    <div class="row">
        <div class="description text col-lg-6 m-0">
            @if ('' !== $item->getSubtitle())
            <div class="subtitle m-0">
                <h6>{{ $item->getSubtitle() }}</h6>
            </div>
            @endif
            {!! $item->getDescriptionSanitized() !!}

            @if ( $item->getLinksArray()->count() )
            <p>
                @foreach($item->getLinksArray() as $link)
                <a href="{{ $link }}" target="_blank">{{ $link }}</a><br>
                @endforeach
            </p>
            @endif

        </div>

        @if($item->getImages()->count() === 1)
        @php
        /**
        * @var $img Images
        */
        $img = $item->getImages()->first()
        @endphp
        <div class="col-12 col-lg-6 text-center m-0 p-0 imageWrapper">
            <img src="/media/images/{{ $img->internal_filename }}"
                 class="mx-auto"
                 width="{{ $img->displayWidth }}"
                 height="{{ $img->displayHeight }}"
                 title="{{ $img->title ?? 'Event Bild' }}"
                 alt="{{ $img->title ?? 'Event Bild' }}"
            >
        </div>
        @elseif ($item->getImages()->count() > 1 )
        <div id="imgCarousel{{ $domID }}"
             class="carousel slide text-center col-12 col-lg-6"
             data-interval="false"
        >
            <!-- Indicators -->
            <ul class="carousel-indicators">
                @foreach($item->getImages() as $index => $img)
                <li data-target="imgCarousel{{ $domID }}" data-slide-to="{{ $index }}"
                    @if($index == 0) class="active" @endif></li>
                @endforeach()
            </ul>

            <div class="carousel-inner text-center col-12 m-0 p-0">
                @foreach($item->getImages() as $index => $img)
                <div class="carousel-item w-100 m-0 p-0 @if($index == 0) active @endif">
                    <img src="/media/images/{{ $img->internal_filename }}"
                         class="mx-auto"
                         width="{{ $img->displayWidth }}"
                         height="{{ $img->displayHeight }}"
                         title="{{ $img->title ?? 'Event Bild' }}"
                         alt="{{ $img->title ?? 'Event Bild' }}"
                    >
                    @if('' != $img->title)
                    <div class="carousel-caption">
                        <h3>{{ $img->title }}</h3>
                    </div>
                    @endif
                </div>
                @endforeach()
            </div>

            <a class="carousel-control-prev" href="#imgCarousel{{ $domID }}" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Zurück</span>
            </a>
            <a class="carousel-control-next" href="#imgCarousel{{ $domID }}" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Weiter</span>
            </a>
        </div>
        @endif

    </div>
</div>