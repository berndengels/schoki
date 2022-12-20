@php
    use App\Models\Images, Carbon\Carbon;
    $domID = $item->getDomId();
    $eventDate = new Carbon($item->getEventDate());
@endphp
<div class="collapseToggle mbs">
    <div class="eventHeader col-12">
        <div class="subHeader m-0 p-0">
            <span class="ml-2">{{ __($eventDate->format('l')) }}</span>
            <span class="ml-1">{{ $eventDate->format('d.m.') }}</span>
            <span class="ml-1">{{ $item->getEventTime() }} Uhr</span>

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
            <div class="theme p-0 pl-2 m-0">
                <h7>{{ $item->getTheme()->name }}</h7>
            </div>
        @endif

        <div class="title m-0 p-0">
            <div class="ml-2 p-0">
                {{ $item->getTitle() }}
            </div>
        </div>

        <div class="header-buttons d-inline-block clearfix m-0 p-0 pr-2">
            @if($item->getTicketlink())
                <a class="ticketlink"
                   role="button"
                   title="zum Ticket Shop"
                   target="_blank"
                   href="{{ $item->getTicketlink() }}">
                    <ion-icon name="ticket"></ion-icon>
                    <span class="d-none d-md-inline-block">TicketShop</span></a>
            @endif
            <button class="btn-toggle off"
                    role="button"
                    data-toggle="collapse"
                    aria-expanded="false"
                    data-target="#{{ $domID }}"
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
             data-ride="carousel"
             data-interval="4000"
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

    @if ('' !== $item->getSubtitle())
        <div class="subtitle col-12 m-0 p-2">
            <h6>{{ $item->getSubtitle() }}</h6>
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
