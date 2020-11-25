@extends('layouts.public')

@section('title', 'Events')

@section('extra-headers')
    <link rel="stylesheet" href="{{ asset('vendor/calendar/css/zabuto_calendar.min.css') }}">
    <script src="{{ asset('vendor/calendar/js/zabuto_calendar.min.js') }}"></script>
@endsection

@section('header-content')
    <!--div class="d-none col-md-auto">
        {--{ $data->links() }--}
    </div-->
@endsection

@section('content')
    <div class="eventContainer col-sm-11 col-md-9 mbs">
        @if( $data->count() )

            @foreach ($data as $event)
                <div class="event col-12 lazy" >
                    <div class="eventContent col-12">
                        <div class="eventHeader col-12">

                            <div class="dateWrapper col-4 col-md-3">
                                <div class="weekday col-12">
                                    <a data-toggle="collapse" href="#{{ $event->getDomId() }}">{{ $event->getEventDate()->formatLocalized('%A') }}</a>
                                </div>
                                @if($event->getCategory())
                                    <ion-icon name="{{ $event->getCategory()->icon }}"></ion-icon>
                                @endif

                                <div class="eventDate col-6">
                                    <a data-toggle="collapse" href="#{{ $event->getDomId() }}">{{ $event->getEventDate()->formatLocalized('%d.%m.%Y') }}</a>
                                </div>
                                <div class="eventTime col-6">
                                    <a data-toggle="collapse" href="#{{ $event->getDomId() }}">{{ $event->getEventTime() }} Uhr</a>
                                </div>
                            </div>

                            <div class="title col-8 col-md-9">
                                <div class="col-12">{{ $event->getTitle() }}</div>
                            </div>
                        </div>

                        <div id="{{ $event->getDomId() }}" class="eventBody collapse col-12">

                            @if($event->getImages()->count() === 1)
                                {? $img = $event->getImages()->first() ?}
                                <div class="col-12 text-center">
                                    <img data-src="/media/images/cropped/{{ $img->internal_filename }}"
                                         class="d-block w-100"
                                         alt="{{ $img->title }}">
                                </div>
                            @elseif ($event->getImages()->count() > 1 )
                                <div id="imgCarousel{{ $event->getId() }}"
                                     class="carousel slide text-center col-12"
                                     data-ride="carousel"
                                     data-interval="5000"
                                >
                                    <div class="carousel-inner text-center col-12 text-center w-100">
                                        @foreach($event->getImages() as $index => $img)
                                            <div class="carousel-item @if($index == 0) active @endif">
                                                <img data-src="/media/images/{{ $img->internal_filename }}"
                                                     class="d-block w-100"
                                                     width="533"
                                                     height="300"
                                                     alt="{{ $img->title }}">
                                            </div>
                                        @endforeach()
                                    </div>

                                    <a class="carousel-control-prev" href="#imgCarousel{{ $event->getId() }}" role="button" data-slide="prev">
                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                        <span class="sr-only">Zurück</span>
                                    </a>
                                    <a class="carousel-control-next" href="#imgCarousel{{ $event->getId() }}" role="button" data-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        <span class="sr-only">Weiter</span>
                                    </a>
                                </div>
                            @endif

                            @if ('' !== $event->getSubtitle())
                                <div class="subtitle col-12">{{ $event->getSubtitle() }}</div>
                            @endif
                            <div class="text col-12">{!! $event->getDescription() !!}</div>
                            @if ( $event->getLinks() && $event->getLinks()->count() )
                                <div class="links">
                                    @foreach($event->getLinks() as $link)
                                        <a href="{{ $link }}" target="_blank">{{ $link }}</a><br>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach

        @endif
    </div>

@endsection

@section('sidebarRight')
    <div class="sidebar-right d-none d-md-block col-md-3">
        <div id="calendar"></div>
    </div>
@endsection

@section('inline-scripts')
    <script>
        $(function($) {
            var now = new Date();
            var year = now.getFullYear();
            var month = now.getMonth() + 1;

            $("#calendar").zabuto_calendar({
                language: 'de',
                year: year,
                month: month,
                show_previous: false,
                show_next: 6,
                cell_border: true,
                today: true,
                show_days: true,
                weekstartson: 1,
                nav_icon: {
                    prev: '<ion-icon name="arrow-dropleft-circle"></ion-icon>',
                    next: '<ion-icon name="arrow-dropright-circle"></ion-icon>'
                },
                ajax: {
                    url: "/calendar/" + year + "/" + month,
                    modal: true,
                },
                legend: false, // object array, [{type: string, label: string, classname: string}]
/*
                action: function(e) {
                    var $this = $(this),
                        date = $this.data('date'),
                        hasEvent = $this.data('hasEvent');
                    if(hasEvent) {
                        $.post("/events/info", {
                            _token: $('[name="csrf-token"]').attr('content'),
                            date: date
                        })
                        .done(function(response){
                            console.info(response);
                        })
                        .fail(function(err){

                        });
                    }
                    console.info($this.data());
                },
*/
                action_nav: false // function
            });
        });

        $(".collapse").on('shown.bs.collapse', function(){
            $this = $(this);
            $this.find('.carousel').carousel('cycle');
            //$this.parent('.event').removeClass('col-md-4').addClass('col-md-6');
        });$(".collapse").on('show.bs.collapse', function(){
            $this = $(this);
            $('.collapse').each(function(){
                if($this != $(this)) {
                    $(this).collapse('hide');
                }
            });
//        $this.parent('.event').siblings().find('.collapse').collapse('hide');
        });
        $(".collapse").on('hide.bs.collapse', function(){
            $this = $(this);
            //$this.parent('.event').removeClass('col-md-6').addClass('col-md-4');
        });
        $(".collapse").on('hidden.bs.collapse', function(){
        });

    </script>

@endsection

