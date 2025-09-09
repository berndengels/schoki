@php
	use App\Models\Images, Carbon\Carbon, App\Entities\EventEntity;
	$domID = $item->getDomId();
	$eventDate = new Carbon($item->getEventDate());
/**
* @var $item EventEntity
*/
@endphp

<div class="container">
		<div class="title position-relative">
			<a href="#{{ $domID }}" data-bs-toggle="collapse" aria-expanded="false" aria-controls="{{ $domID }}" role="button">
			<div class="d-flex flex-column flex-md-row">
				<div class="date col-sm-3 mb-0">
					@if($item->getCategory())
						<h6 class="category">
						@if($item->getCategory()->icon)
							<?php /* <ion-icon name="{{ $item->getCategory()->icon }}" title="{{ $item->getCategory()->name }}"></ion-icon> */ ?>
						@endif
						{{ $item->getCategory()->name }}
               			</h6>
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
						<h6>
							<span class="promoter p-0 m-0">{{ $item->getTheme()->name }} {{ $item->getPromoter() }}</span>
						</h6>
					@else
						<h6><span class="promoter p-0 m-0">&nbsp;</span></h6>
					@endif
					<h2 class="fw-bold">{{ $item->getTitle() }}</h2>
					@if ('' !== $item->getDj())
						<div class="m-0">
							<h6 class="subtitle">{{ $item->getDj() }}</h6>
						</div>
					@endif
				</div>
			</div>
			</a>
		</div>
</div>

<div class="container">
	<div id="{{ $domID }}" data-event-date="{{ $item->getEventDate() }}" class="info mt-5 collapse">
		<div class="d-flex">
			<div class="row">
				<div class="col-sm-3">
					<div class="event-facts">
						@if($item->getTicketlink())
							<p><strong>Tickets</strong><br>
							<a href="{{ $item->getTicketlink() }}">Tickettoaster</a></p>
						@endif

						@if ( $item->getLinksArray()->count() )
							<p class="mb-0"><strong>Links</strong></p>
							<p class="event-links">
								@foreach($item->getLinksArray() as $link)
									<a href="{{ $link }}" target="_blank" class="d-block">{{ $link }}</a>
								@endforeach
							</p>
						@endif
					</div>
				</div>

				<div class="col-sm-9 offset-sm-3-">
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
						<div class="m-0 p-0 imageWrapper">
							<img src="/media/images/{{ $img->internal_filename }}"
								 class="img-fluid"
								 title="{{ $img->title ?? 'Event Bild' }}"
								 alt="{{ $img->title ?? 'Event Bild' }}"
							>
						</div>
					@elseif ($item->getImages()->count() > 1 )
						<div id="imgCarousel{{ $domID }}"
							 class="carousel slide carousel-fade"
							 data-interval="false"
						>
							<!-- Indicators -->
							<div class="carousel-indicators">
								@foreach($item->getImages() as $index => $img)
									<button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="{{ $index }}" @if($index == 0) class="active" aria-current="true"@endif aria-label="Slide {{ $index }}"></button>
								@endforeach()
							</div>

							<div class="carousel-inner">
								@foreach($item->getImages() as $index => $img)
									<div class="carousel-item @if($index == 0) active @endif">
										<img src="/media/images/{{ $img->internal_filename }}"
											 class="img-fluid"
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

							<button class="carousel-control-prev" type="button" data-bs-target="#imgCarousel{{ $domID }}" data-bs-slide="prev">
								<span class="carousel-control-prev-icon" aria-hidden="true"></span>
								<span class="visually-hidden">Previous</span>
							</button>

							<button class="carousel-control-next" type="button" data-bs-target="#imgCarousel{{ $domID }}" data-bs-slide="next">
								<span class="carousel-control-next-icon" aria-hidden="true"></span>
								<span class="visually-hidden">Next</span>
							</button>

						</div>
					@endif

				</div>
			</div>
		</div>
	</div>
</div>


