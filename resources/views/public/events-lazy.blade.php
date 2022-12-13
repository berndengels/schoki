@extends('layouts.public')

@section('title', 'Events')

@section('extra-headers')
    <link rel="stylesheet" href="{{ mix('vendor/calendar/css/zabuto_calendar.min.css') }}">
    <script src="{{ mix('vendor/calendar/js/zabuto_calendar.min.js') }}"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
@endsection

@section('header-content')
@endsection

@section('content')
    <div class="eventContainer col-sm-11 col-md-6 mt-1 ml-lg-4">
        <h3 class="events-header d-block">Veranstaltungen</h3>
        @if( $data->count() )
            <div class="row ml-0 p-0">
                {{ $data->links() }}
            </div>
            @foreach ($data as $event)
                <div class="event col-12">
                    <div class="eventContent container col-12 mb-2">
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

		function logDescription(){
			$(".collapse", ".eventContainer").each((k,el) => {
				console.info(el.id, $(el).find(".description").is(":visible"));
            });
        }
		function removeAllDescription() {
			$(".collapse .description", ".eventContainer").find().each(() => $(this).html(""));
        }
		function loadDescription(domId, date) {
			removeAllDescription();
			$("#" + domId + " .description", ".eventContent").load("/api/eventDescriptionByDate/" + date);
        }
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
	                    $btn = $first.prev('.collapseToggle').find('.btn-toggle').removeClass('off').addClass('on');

	                history.push($btn);

                    $first.collapse('show');

	                $first.on('shown.bs.collapse', function() {
	                    loadDescription($first.attr('id'), $first.data("event-date"));
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
		                $btn.removeClass('on').addClass('off');
                    } else {
		                $btn.removeClass('off').addClass('on');
                    }
					if(history.length > 1) {
						let $last = $(history.shift()),
                            $other = $last
    							.parent('.eventHeader')
    							.parent('.collapseToggle')
    							.next('.collapse')
                                .closest('.event')
                                .find('.show');

						$other.collapse('hide');
						$last.removeClass('on').addClass('off')
                    }
                });

                $('.collapse', '.eventContainer')
                    .on('shown.bs.collapse', function() {
                        const my = this, $my = $(my),
                            $header = $(my).prev('.collapseToggle'),
                            top = parseInt($header.offset().top - 70, 10),
                            $carousel = $('.carousel', my);

	                    $header.find('.btn-toggle').removeClass('off').addClass('on');
	                    loadDescription($my.attr('id'), $my.data("event-date"));

	                    $([document.documentElement, document.body]).animate({
                            scrollTop: top
                        }, scrollDelay);

                        if($carousel.length) {
                            $carousel.carousel("cycle");
                        }
                    })
                    .on('show.bs.collapse', function() {
                        const my = this, $my = $(my),
                            $other = $(my).closest('.event').siblings().find('.show');
	                    $(my).prev('.collapseToggle').find('.btn-toggle').removeClass('off').addClass('on');
	                    loadDescription($my.attr('id'), $my.data("event-date"));

                        $other.collapse('hide');
                    })
	                .on('hide.bs.collapse', function() {
		                const my = this,
			                $carousel = $('.carousel', my);

	                    $(my).prev('.collapseToggle').find('.btn-toggle').removeClass('on').addClass('off');
		                removeAllDescription();
		                if($carousel.length) {
			                $carousel.carousel("dispose");
		                }
		                console.clear()
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
	            prev: '<ion-icon name="caret-back-circle-outline"></ion-icon>',
	            next: '<ion-icon name="caret-forward-circle-outline"></ion-icon>'
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
