@extends('layouts.public')

@section('title', 'Events')

@section('extra-headers')
    <link rel="stylesheet" href="{{ mix('vendor/calendar@2/zabuto_calendar.min.css') }}">
    <script src="{{ mix('vendor/calendar@2/zabuto_calendar.min.js') }}"></script>
@endsection

@section('header-content')
@endsection

@section('content')
    @if( $data->count() )
        @foreach ($data as $event)
            <div class="event">
                <x-event-view :item="$event" :index="$loop->index" />
            </div>
         @endforeach
        <div class="pages">{{ $data->links() }}</div>
    @else
        <h5 class="w-100 text-center mt-5 mbs">Sorry, keine Daten vorhanden</h5>
    @endif
@endsection

@section('sidebarRight')
    <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasCalendar" aria-labelledby="offcanvasCalendarLabel">
        <div class="offcanvas-header">
            <h4 class="offcanvas-title" id="offcanvasCalendarLabel">Event Kalender</h4>
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
                .on('shown.bs.collapse', () => {
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
                .on('show.bs.collapse', () => {
                    const my = this, $my = $(my),
                    $other = $(my).closest('.event').siblings().find('.show');
                    //$(my).prev('.collapseToggle').find('.btn-toggle').removeClass('off').addClass('on').html('close');
                    $(my).prev('.collapseToggle').find('.btn-toggle').removeClass('off').addClass('on');
                    loadDescription($my.attr('id'), $my.data("event-date"));

                    $other.collapse('hide');
                })
                .on('hide.bs.collapse', () => {
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