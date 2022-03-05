@extends('layouts.public')

@section('title', 'Events')

@section('extra-headers')
    <link rel="stylesheet" href="{{ asset('vendor/calendar/css/zabuto_calendar.min.css') }}">
    <script src="{{ asset('vendor/calendar/js/zabuto_calendar.min.js') }}"></script>
@endsection

@section('header-content')
@endsection

@section('content')
    <div class="eventContainer col-sm-11 col-md-6 mt-1 ml-lg-4">
        @if( $data->count() )
            @foreach ($data as $event)
                <div class="event col-12 lazy">
                    <div id="eventContent" class="eventContent container col-12 mb-2">
                    @include('public.templates.event')
                    </div>
                </div>
            @endforeach
        @else
            <h5 class="w-100 text-center mt-5 mbs">Sorry, keine Daten vorhanden</h5>
        @endif
    </div>

@endsection

@section('sidebarRight')
    <div class="sidebar-right d-none d-md-block col-md-4 ml-0 ml-lg-2">
        <div class="header">
            <ion-icon name="calendar"></ion-icon>
            <span>Event Kalender</span>
        </div>

        <div id="calendar" class="m-0 p-0"></div>
    </div>

    @if(isset($schokiStyle))
    <!--div class="row">Style: {{ $schokiStyle }}</div-->
    @endif

@endsection

@section('inline-scripts')
    <script>
        $(function($) {
            var scrollDelay = 0,
                collapseItems = [],
                firstLoad = true;

            $(".lazy").lazy({
                scrollDirection: 'vertical',
                effect: 'fadeIn',
                visibleOnly: true,
                treshold: 100,
            });
            $([document.documentElement, document.body]).animate({
                scrollTop: 0
            }, 0);
            $(document)
                .ajaxStart(function( event ) {
                    $(".collapse").unbind('show.bs.collapse');
                })
                .ajaxStop(function( event ) {
                    if(firstLoad) {
                        var $first = $('.collapse:first', '.eventContainer'),
                            $header = $first.prev('.collapseToggle');

                        $first.on('shown.bs.collapse', function() {
                            var $carousel = $('.carousel', this);
                            if($carousel.length) {
                                $carousel.carousel("cycle");
                            }
                        });
                        $header.find('button').html('close');
                        $first.collapse('show');
                        firstLoad = false;
                    }

                    $('button[data-toggle="collapse"]', '.eventContainer').click(function(){
                        var $this = $(this),txt = ('open' === $this.html()) ? 'close' : 'open';
                        $this.html(txt);
                    });

                    $('.collapse', '.eventContainer')
                        .on('shown.bs.collapse', function() {
                            var my = this, id = my.id,
                                $header = $(my).prev('.collapseToggle'),
                                top = parseInt($header.offset().top - 70, 10),
                                $carousel = $('.carousel', my)
                            ;

                            $([document.documentElement, document.body]).animate({
                                scrollTop: top
                            }, scrollDelay);

                            if($carousel.length) {
                                $carousel.carousel("cycle");
                            }
                        })
                        .on('show.bs.collapse', function() {
                            var id = this.id, my = this,
                                $other = $(my).closest('.event').siblings().find('.show');

                            console.info('show.bs.collapse: '+ id + " siblings: " + $other.length);
                            $other.collapse('hide');
                        })
                        .on('hide.bs.collapse', function() {
                            var id = this.id,
                                my = this,
                                $carousel = $('.carousel', my),
                                $header = $(my).prev('.collapseToggle')
                            ;
                            if($carousel.length) {
                                $carousel.carousel("dispose");
                            }
                            console.info('hide.bs.collapse: '+ id + " collapsed: " + collapseItems.length);
                        })
                    ;
            });

            var now = new Date();
            var year = now.getFullYear();
            var month = now.getMonth() + 1;

            $("#calendar").zabuto_calendar({
                language: 'de',
//                year: year,
//                month: month,
                show_previous: false,
                show_next: 6,
                cell_border: false,
                today: true,
                show_days: true,
                weekstartson: 1,
                nav_icon: {
                    prev: '<ion-icon name="arrow-dropleft-circle"></ion-icon>',
                    next: '<ion-icon name="arrow-dropright-circle"></ion-icon>'
                },
                ajax: {
//	                url: "/calendar/" + year + "/" + month,
	                url: "/calendar",
                    modal: true,
                },
                legend: false, // object array, [{type: string, label: string, classname: string}]
            });
        });

    </script>

@endsection

