
@if(isset($backLink) && $backLink)
    @include('components.newsletter-back')
@endif

Schokoladen Berlin-Mitte, Ackerstrasse 169, 10115 Berlin, https://www.schokoladen-berlin.de

@if($title)
====================================
{{ $title }}
====================================
@endif

@if($header){{ $header }}@endif

@if($data && $data->count())
@foreach($data as $item)
{!! $item->getEventDate()->format('d.m.Y') !!} {!! $item->getEventTime() !!} {{ $item->getCategory()->name }} {{ $item->getTitle() }}
@if($item->getSubtitle())
{{ $item->getSubtitle() }}
@endif
@if($item->getTheme())
{{ $item->getTheme()->name }}
@endif

@if($item->getDescription())
{!! $item->getDescriptionText() !!}
@endif

@if($item->getLinks()->count())
@foreach($item->getLinks() as $link)
{{ $link }}
@endforeach
@endif
-------------------------------------

@endforeach
@endif
