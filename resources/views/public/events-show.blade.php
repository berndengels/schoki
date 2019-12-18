@extends('layouts.public')

@section('title', 'Event')

@section('content')

    <div class="eventShow col-12 col-lg-8 mt-2 mbs">

        @if($event)

        <div class="eventHeader">

            <div class="">
                <h3>{{ $event->getEventDate()->formatLocalized('%A') }} {{ $event->getEventDate()->formatLocalized('%d.%m.%Y') }} {{ $event->getEventTime() }} Uhr <span class="category">{{ $event->getCategory()->name }}</span></h3>
            </div>

            @if($event->getTheme())
            <div class="">
                <span>Thema: {{ $event->getTheme()->name }}</span>
            </div>
            @endif

            <div class="title">
                <div class="">
                    <h5>{{ $event->getTitle() }}</h5>
                </div>
            </div>
        </div>

        <div class="">
            @if($event->getImages()->count() === 1)
                {? $img = $event->getImages()->first() ?}
                <div class="text-center w-100 m-0 p-0 imageWrapper">
                    <img src="/media/images/{{ $img->internal_filename }}"
                         class="w-auto m-auto"
                         title="{{ $img->title }}"
                         alt="{{ $img->title }}"
                    >
                </div>
            @elseif ($event->getImages()->count() > 1 )
                <div id="imgCarousel"
                     class="carousel slide text-center"
                     data-ride="carousel"
                     data-interval="4000"
                >
                    <!-- Indicators -->
                    <ul class="carousel-indicators">
                        @foreach($event->getImages() as $index => $img)
                            <li data-target="imgCarousel" data-slide-to="{{ $index }}" @if($index == 0) class="active" @endif></li>
                        @endforeach()
                    </ul>

                    <div class="carousel-inner text-center w-100 m-0 p-0">
                        @foreach($event->getImages() as $index => $img)
                            <div class="carousel-item w-100 m-0 p-0 @if($index == 0) active @endif">
                                <img src="/media/images/{{ $img->internal_filename }}"
                                     class="w-auto m-auto"
                                     title="{{ $img->title }}"
                                     alt="{{ $img->title }}"
                                >
                                @if('' != $img->title)
                                    <div class="carousel-caption">
                                        <h3>{{ $img->title }}</h3>
                                    </div>
                                @endif
                            </div>
                        @endforeach()
                    </div>

                    <a class="carousel-control-prev" href="#imgCarousel" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Zurück</span>
                    </a>
                    <a class="carousel-control-next" href="#imgCarousel" role="button" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Weiter</span>
                    </a>
                </div>
            @endif
            @if ('' !== $event->getSubtitle())
                <div class="mt-3">
                    <h5>{{ $event->getSubtitle() }}</h5>
                </div>
            @endif

            <div class="">
                {!! $event->getDescriptionSanitized() !!}
            </div>

            @if ( $event->getLinks() && $event->getLinks()->count() )
                <div class="">
                    @foreach($event->getLinks() as $link)
                        <a href="{{ $link }}" target="_blank">{{ $link }}</a><br>
                    @endforeach
                </div>
            @endif
        </div>
    @elseif($expired)
         <h4>Das Datum dieses Events ist bereits abgelaufen.</h4>
    @else
         <h4>Kein Daten gefunden.</h4>
    @endif
    </div>
@endsection

