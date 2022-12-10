@extends('layouts.public')

@section('title', 'Events')

@section('extra-headers')
    <link rel="stylesheet" href="{{ mix('vendor/calendar/css/zabuto_calendar.min.css') }}">
    <script src="{{ mix('vendor/calendar/js/zabuto_calendar.min.js') }}"></script>
@endsection

@section('header-content')
@endsection

@section('content')
    <div class="eventContainer col-sm-11 col-md-6 mt-1 ml-lg-4">
        <h3 class="events-header d-block">Veranstaltungen</h3>
        @if( $data->count() )
            <div class="row ml-0 p-0">{{ $data->links() }}</div>
            @foreach ($data as $event)
                <div class="event col-12 lazy">
                    <div id="eventContent" class="eventContent container col-12 mb-2">
                    {{-- @include('public.templates.event') --}}
                    <x-event-view :item="$event" :index="$loop->index" />
                    </div>
                </div>
            @endforeach
            <div class="row ml-0 mt-2 pl-0">{{ $data->links() }}</div>
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
@endsection

@section('inline-scripts')
<script>
    $(function($) {
        var scrollDelay = 0,
            history = [],
            firstLoad = true;

		function getEvent(el) {
            console.info('getEvent', el)
        }
/*
        $(".lazy").lazy({
            scrollDirection: 'vertical',
            effect: 'fadeIn',
            visibleOnly: true,
            treshold: 100,
        });
*/
        $([document.documentElement, document.body]).animate({
            scrollTop: 0
        }, 0);
        $(document)
            .ajaxStart(function() {
                $(".collapse").unbind('show.bs.collapse');
            })
            .ajaxStop(function() {
                if(firstLoad) {
	                const $first = $('.collapse:first', '.eventContainer'),
	                $btn = $first.prev('.collapseToggle').find('.btn-toggle').removeClass('off').addClass('on').html('close');
	                history.push($btn);

                    $first.collapse('show');

	                $first.on('shown.bs.collapse', function() {
		                var $carousel = $('.carousel', this);
		                if($carousel.length) {
			                $carousel.carousel("cycle");
		                }
	                });
                    firstLoad = false;
                }

                $('.btn-toggle', '.eventContainer').click((e) => {
                    const $btn = $(e.target);

                    history.push($btn)
	                if($btn.hasClass('on')) {
		                $btn.removeClass('on').addClass('off').html('open');
                    } else {
		                $btn.removeClass('off').addClass('on').html('close');
                    }
					if(history.length > 1) {
						let $last = $(history.shift()),
                            $other = $last
    							.parent('.eventHeader')
    							.parent('.collapseToggle')
    							.next('.collapse')
                                .closest('.event')
                                .find('.show');

						console.info($other)
						$other.collapse('hide');

						$last.removeClass('on').addClass('off').html('open')
                    }
                });

                $('.collapse', '.eventContainer')
                    .on('shown.bs.collapse', function() {
                        const my = this,
                            $header = $(my).prev('.collapseToggle'),
                            top = parseInt($header.offset().top - 70, 10),
                            $carousel = $('.carousel', my);

	                    $header.find('.btn-toggle').removeClass('off').addClass('on');

	                    $([document.documentElement, document.body]).animate({
                            scrollTop: top
                        }, scrollDelay);

                        if($carousel.length) {
                            $carousel.carousel("cycle");
                        }
                    })
                    .on('show.bs.collapse', function() {
                        const my = this,
                            $other = $(my).closest('.event').siblings().find('.show');
	                    $(my).prev('.collapseToggle').find('.btn-toggle').removeClass('off').addClass('on');

                        $other.collapse('hide');
                    })
	                .on('hide.bs.collapse', function() {
		                const id = this.id,
			                my = this,
			                $carousel = $('.carousel', my);

	                    $(my).prev('.collapseToggle').find('.btn-toggle').removeClass('on').addClass('off');

		                if($carousel.length) {
			                $carousel.carousel("dispose");
		                }
	                })
                ;
        });
        $("#calendar").zabuto_calendar({
            language: 'de',
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
                url: "/calendar",
                modal: true,
            },
            legend: false, // object array, [{type: string, label: string, classname: string}]
        });
    });

</script>
@endsection
