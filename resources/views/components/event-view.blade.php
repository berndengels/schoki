@php
    use App\Models\Images, Carbon\Carbon;
    $domID = $item->getDomId();
    $eventDate = new Carbon($item->getEventDate());
@endphp
<div class="collapseToggle mbs">
    <div class="eventHeader col-12">
        <div class="subHeader m-0 p-0">
            <span class="ms-2">{{ __($eventDate->format('l')) }}</span>
            <span class="ms-1">{{ $eventDate->format('d.m.') }}</span>
            <span class="ms-1">{{ $item->getEventTime() }} Uhr</span>

            @if($item->getCategory())
                <span class="category mr-1">
                    @if($item->getCategory()->icon)
                        <ion-icon name="{{ $item->getCategory()->icon }}"
                                  title="{{ $item->getCategory()->name }}"></ion-icon>
                    @endif
                    {{ $item->getCategory()->name }}
                </span>
            @endif
        </div>

        @if ($item->getTheme())
            <div class="theme p-0 ms-2 m-0">
                <h7>{{ $item->getTheme()->name }}</h7>
            </div>
        @endif

        @if($item->getPromoter())
            <div class="title m-0 p-0">
                <div class="ms-2 p-0">
                    Promoter: {{ $item->getPromoter() }}
                </div>
            </div>
        @endif

        <div class="title m-0 p-0">
            <div class="ms-2 p-0">
                {{ $item->getTitle() }}
            </div>
        </div>

        <div class="header-buttons">
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
                    data-bs-toggle="collapse"
                    data-bs-target="#{{ $domID }}"
                    aria-expanded="false"
                    aria-controls="{{ $domID }}">open
            </button>
        </div>
    </div>
</div>

<div id="{{ $domID }}" data-event-date="{{ $item->getEventDate() }}" class="eventBody collapse col-12 mt-0 pt-0">
    @if($item->getImages()->count() === 1)
        @php
            /**
            * @var $img Images
            */
            $img = $item->getImages()->first()
        @endphp
        <div class="col-12 text-center m-0 p-0 imageWrapper">
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
             class="carousel slide text-center col-12"
             data-bs-ride="carousel"
             data-bs-interval="4000"
        >
            <!-- Indicators -->
            <ul class="carousel-indicators">
                @foreach($item->getImages() as $index => $img)
                    <li data-bs-target="#imgCarousel{{ $domID }}" data-bs-slide-to="{{ $index }}"
                        @if($index == 0) class="active" @endif></li>
                @endforeach()
            </ul>

            <div class="carousel-inner text-center col-12 m-0 p-0">
                @foreach($item->getImages() as $index => $img)
                    <div class="carousel-item w-100 m-0 p-0 @if($index == 0) active @endif">
                        <img src="{{ asset('media/images/' . $img->internal_filename) }}"
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

            <button type="button" class="carousel-control-prev" data-bs-target="#imgCarousel{{ $domID }}" role="button" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Zurück</span>
            </button>
            <button type="button" class="carousel-control-next" data-bs-target="#imgCarousel{{ $domID }}" role="button" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Weiter</span>
            </button>
        </div>
    @endif

    @if ($item->getDj())
        <div class="subtitle col-12 m-0 p-2">
            <h6>{{ $item->getDj() }}</h6>
        </div>
    @endif

    <div class="description text col-12 m-0 p-2">
        {!! $item->getDescriptionSanitized() !!}
    </div>
    @if ( $item->getLinksArray()->count() )
        <div class="links col-12 m-0 p-2">
            @foreach($item->getLinksArray() as $link)
                <a href="{{ $link }}" target="_blank">{{ $link }}</a><br>
            @endforeach
        </div>
    @endif
</div>
