@extends('layouts.public')

@section('title', 'Eventsss')

@section('extra-headers')
    <link rel="stylesheet" href="{{ mix('vendor/calendar@2/zabuto_calendar.min.css') }}">
    <script src="{{ mix('vendor/calendar@2/zabuto_calendar.min.js') }}"></script>
@endsection

@section('header-content')
    <!--div class="d-none col-md-auto">
        {--{ $data->links() }--}
    </div-->
@endsection

@section('content')
    @php
        use App\Models\Images, Carbon\Carbon, App\Entities\EventEntity;
    @endphp

    <div class="eventContainer col-sm-11 col-md-9 mbs">
        @if( $data->count() )
            @foreach ($data as $event)
                @php
					/**
                    * @var $event EventEntity
                    */
					$domID = $event->getDomId();
					$eventDate = new Carbon($event->getEventDate());
                @endphp
                <div class="event col-12 lazy" >
                    <div class="eventContent col-12">
                        <div class="eventHeader col-12">

                            <div class="dateWrapper col-4 col-md-3">
                                <div class="weekday col-12">
                                    <a data-toggle="collapse" href="#{{ $domID }}">{{ __($eventDate->format('l')) }}</a>
                                </div>
                                @if($event->getCategory())
                                    <ion-icon name="{{ $event->getCategory()->icon }}"></ion-icon>
                                @endif

                                <div class="eventDate col-6">
                                    <a data-toggle="collapse" href="#{{ $domID }}">{{ $eventDate->format('d.m.Y') }}</a>
                                </div>
                                <div class="eventTime col-6">
                                    <a data-toggle="collapse" href="#{{ $domID }}">{{ $event->getEventTime() }} Uhr</a>
                                </div>
                            </div>

                            <div class="title col-8 col-md-9">
                                @if($event->getPromoter())
                                    <div class="col-12">{{ $event->getPromoter() }}</div>
                                @endif
                                <div class="col-12">{{ $event->getTitle() }}</div>
                            </div>
                        </div>

                        <div id="{{ $domID }}" class="eventBody collapse col-12">

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

                            @if ($event->getDj())
                                <div class="dj col-12">{{ $event->getDj() }}</div>
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
                prev: '<ion-icon name="caret-back-circle-outline"></ion-icon>',
                next: '<ion-icon name="caret-forward-circle-outline"></ion-icon>'
            },
            ajax: {
                url: "/calendar",
                modal: true,
            },
            legend: false, // object array, [{type: string, label: string, classname: string}]
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

