@php
	use App\Models\Images, Carbon\Carbon, App\Entities\EventEntity;
	$domID = $item->getDomId();
	$eventDate = new Carbon($item->getEventDate());
/**
* @var $item EventEntity
*/
@endphp

<div class="ribbon">
	@if($item->getTicketlink())
		<a class="d-block p-2 text-center fw-bold" href="{{ $item->getTicketlink() }}" style="font-size: 1.25rem;">
			<span class="category">Tickets</span>
		</a>
	@endif
</div>
<div class="container">
		<div class="title position-relative">
			<a href="#{{ $domID }}" data-bs-toggle="collapse" aria-expanded="false" aria-controls="{{ $domID }}">
			<div class="d-flex flex-column flex-md-row">
				<div class="date col-sm-3 mb-0">
					@if($item->getCategory())
						<h7 class="category">
                    @if($item->getCategory()->icon)
						<?php /* <ion-icon name="{{ $item->getCategory()->icon }}" title="{{ $item->getCategory()->name }}"></ion-icon> */ ?>
					@endif
						{{ $item->getCategory()->name }}
               			</h7>
					@endif

					<div class="fw-bold">
						<p class="h3 mb-0" style="white-space: nowrap;">
							<span class="">{{ __($eventDate->format('l')) }}</span><br />
							<span class="">{{ $eventDate->format('d.m.') }}</span><br />
							<span class="d-none">{{ $item->getEventTime() }} Uhr</span>
						</p>
					</div>
				</div>
				<div class="col-sm-9" style="overflow: hidden;">
					@if ($item->getTheme())
						<h7>
							<span class="promoter p-0 m-0">{{ $item->getTheme()->name }}</span>
						</h7>
					@endif
					<h2 class="fw-bold">{{ $item->getTitle() }}</h2>
					@if ('' !== $item->getSubtitle())
						<div class="m-0">
							<h6 class="subtitle">Party: {{ $item->getSubtitle() }}</h6>
						</div>
					@endif

					<div class="marquee d-none">
						<div class="marquee__content">
							<h2 class="fw-bold">{{ $item->getTitle() }}</h2>
						</div>
						<div class="marquee__content" aria-hidden="true">
							<h2 class="fw-bold">{{ $item->getTitle() }}</h2>
						</div>
					</div>
				</div>
			</div>
			</a>
		</div>
	</div>

<div class="container">
	<div id="{{ $domID }}" data-event-date="{{ $item->getEventDate() }}" class="info mt-5 collapse">
		<div class="d-flex">
			<div class="row">
				<div class="col-sm-9 offset-sm-3">
					<div class="event-description">
						{!! $item->getDescriptionSanitized() !!}
					</div>
				</div>
				<div class="col-sm-9 offset-sm-3">
					@if($item->getImages()->count() === 1)
						@php
							/**
                            * @var $img Images
                            */
                            $img = $item->getImages()->first()
						@endphp
						<div class="text-center m-0 p-0 imageWrapper">
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

					@if ( $item->getLinksArray()->count() )
						<p class="event-links mt-3">
							@foreach($item->getLinksArray() as $link)
								<a href="{{ $link }}" target="_blank" class="d-block">{{ $link }}</a>
							@endforeach
						</p>
					@endif

					<div class="embed-responsive embed-responsive-16by9 d-none">
						<iframe class="embed-responsive-item" width="560" height="315" src="https://www.youtube.com/embed/QG5Wf0KR7Qk?si=i0HLna0QzSlaVGw9" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


