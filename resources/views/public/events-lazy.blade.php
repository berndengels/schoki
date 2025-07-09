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
    @if( $data->count() )
        @foreach ($data as $event)
            <div class="event pt-4 pb-4">
                <x-event-view :item="$event" :index="$loop->index" />
            </div>
        @endforeach
        <div class="pages">{{ $data->links() }}</div>
    @else
        <h5 class="w-100 text-center mt-5 mbs">Sorry, keine Daten vorhanden</h5>
    @endif
@endsection

@section('contentBackup')
    <div class="eventContainer col-sm-11- col-md-6- mt-1 ms-lg-4- m-4">
        <h3 class="events-header d-block mb-4">Veranstaltungen</h3>
        @if( $data->count() )
            @foreach ($data as $event)
                <div class="event col-12">
                    <div class="eventContent mb-4">
                        <x-event-view :item="$event" :index="$loop->index" />
                    </div>
                </div>
            @endforeach
            <div class="row ml-0 mt-2 ps-0">{{ $data->links() }}</div>
        @else
            <h5 class="w-100 text-center mt-5 mbs">Sorry, keine Daten vorhanden</h5>
        @endif
    </div>
@endsection

@section('sidebarRight')
    <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasExampleLabel">Event Kalender</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <div id="calendar"></div>
        </div>
    </div>
@endsection

@section('inline-scripts')
<script>
    $(function($) {
        var scrollDelay = 0,
            history = [],
            firstLoad = true;
/*
		function logDescription(){
			$(".collapse", ".eventContainer").each((k,el) => {
				console.info(el.id, $(el).find(".description").is(":visible"));
            });
        }
*/
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
            firstLoad = false;
            if(firstLoad) {
                const $first = $('.collapse:first', '.eventContainer'),
                    $btn = $first.prev('.collapseToggle').find('.btn-toggle')
                        .removeClass('off')
                        .addClass('on')
                        .html("close")
                ;

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
                    //$btn.removeClass('on').addClass('off').html('open');
                    $btn.removeClass('on').addClass('off');
                } else {
                    //$btn.removeClass('off').addClass('on').html('close');
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
                        //top = parseInt($header.offset().top - 70, 10),
                        top = parseInt($header.offset().top - 125, 10),
                        $carousel = $('.carousel', my);

                    //$header.find('.btn-toggle').removeClass('off').addClass('on').html('close');
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
                    //$(my).prev('.collapseToggle').find('.btn-toggle').removeClass('off').addClass('on').html('close');
                    $(my).prev('.collapseToggle').find('.btn-toggle').removeClass('off').addClass('on');
                    loadDescription($my.attr('id'), $my.data("event-date"));

                    $other.collapse('hide');
                })
                .on('hide.bs.collapse', function() {
                    const my = this,
                        $carousel = $('.carousel', my);

                    //$(my).prev('.collapseToggle').find('.btn-toggle').removeClass('on').addClass('off').html('open');
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
    /*document.querySelectorAll('.event').forEach(el => {
        el.addEventListener('mouseover', e => {
            el.lastElementChild.classList.add('show');
        });
        el.addEventListener('mouseleave', e => {
            el.lastElementChild.classList.remove('show');
        });
    });*/
</script>
@endsection

@section('svg-filters')
    <svg xmlns="http://www.w3.org/2000/svg" version="1.1" height="0" width="0">
        <defs>
            <filter id="turbulence">
                <feTurbulence type="fractalNoise" baseFrequency=".05" numOctaves="4" />
            </filter>
            <filter id="displacement">
                <feDisplacementMap in="SourceGraphic" scale="4" />
            </filter>
            <filter id="combined">
                <feTurbulence type="fractalNoise" baseFrequency=".05" numOctaves="4" />
                <feDisplacementMap in="SourceGraphic" scale="4" />
            </filter>
        </defs>
    </svg>
@endsection