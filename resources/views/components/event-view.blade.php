@php
    use App\Models\Images, Carbon\Carbon;
    $domID = $item->getDomId();
    $eventDate = new Carbon($item->getEventDate());
@endphp



    <div class="title position-relative mt-4">
        <div class="ribbon">
            @if($item->getCategory())
                <span class="category">
                    @if($item->getCategory()->icon)
                        <?php /* <ion-icon name="{{ $item->getCategory()->icon }}" title="{{ $item->getCategory()->name }}"></ion-icon> */ ?>
                    @endif
                    {{ $item->getCategory()->name }}
               </span>
            @endif
        </div>
        <div class="d-flex align-items-center" style="border: 1px solid black">
            <div class="date mb-0">
                <div class="text-center m-4 font-weight-bold">
                    <p class="h3 mb-0" style="white-space: nowrap;">
                        <span class="">{{ __($eventDate->format('l')) }}</span><br />
                        <span class="">{{ $eventDate->format('d.m.') }}</span><br />
                        <span class="d-none">{{ $item->getEventTime() }} Uhr</span>
                    </p>
                </div>
            </div>
            <div class="" style="overflow: hidden;">
                @if ($item->getTheme())
                    <h7>
                        <span class="promoter p-0 m-0">{{ $item->getTheme()->name }}</span>
                    </h7>
                @endif
                <div class="marquee">
                    <div class="marquee__content">
                        <h2 class="font-weight-bold">{{ $item->getTitle() }}</h2>
                    </div>
                    <div class="marquee__content" aria-hidden="true">
                        <h2 class="font-weight-bold">{{ $item->getTitle() }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="{{ $domID }}" data-event-date="{{ $item->getEventDate() }}" class="info collapse p-3 p-lg-5">
         <div class="d-flex" style="gap: 3rem;">
            <div class="row">
            <div class="col-lg-6 fira-sans-regular">
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
            <div class="col-lg-6 fira-sans-regular">
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

                <div class="embed-responsive embed-responsive-16by9 d-none">
                    <iframe class="embed-responsive-item" width="560" height="315" src="https://www.youtube.com/embed/QG5Wf0KR7Qk?si=i0HLna0QzSlaVGw9" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                </div>
            </div>
            </div>
        </div>
    </div>

    <div class="links">
        <div class="d-flex ">
            <div class="flex-fill">
                <a class="d-block p-2 text-center font-weight-bold border border-dark border-top-0" href="#{{ $domID }}" style="font-size: 1.25rem;" data-toggle="collapse" aria-expanded="false" aria-controls="{{ $domID }}"><span class="d-none d-md-inline-block">More</span> Info</a>
            </div>
            <div class="flex-fill">
                @if($item->getTicketlink())
                    <a class="d-block p-2 text-center font-weight-bold border border-dark border-top-0" href="{{ $item->getTicketlink() }}" style="font-size: 1.25rem;">Tickets</a>
                @else
                    <span class="d-block p-2 text-center font-weight-bold border border-dark border-top-0" style="font-size: 1.25rem;">Tickets <span class="d-none d-md-inline-block">soon</span></span>
                @endif
            </div>
            <div class="flex-fill">
                <a class="previewFlyer d-block p-2 text-center font-weight-bold border border-dark border-top-0" href="" style="font-size: 1.25rem;">Flyer <span class="d-none d-md-inline-block">Preview</span></a>
            </div>
        </div>
    </div>
    <div class="preview_img">
        @if ($item->getImages()->count() > 0 )
            @php
                /**
                * @var $img Images
                */
                $img = $item->getImages()->first()
            @endphp
            <img class="mx-auto" src="/media/images/{{ $img->internal_filename }}" width="{{ $img->displayWidth }}" height="{{ $img->displayHeight }}" title="{{ $img->title ?? 'Event Bild' }}" alt="{{ $img->title ?? 'Event Bild' }}" />
        @endif
    </div>