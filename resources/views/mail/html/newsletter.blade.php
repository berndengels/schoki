{? $tplURL='https://www.schokoladen-mitte.de' ?}
{? $tplTitle='Schokoladen Berlin-Mitte' ?}
@if(isset($backLink) && $backLink)
    @include('components.newsletter-back')
@endif

<div class="myContent" style="font-family: Helvetica, Verdana, Arial; margin: 10px;">
    <div style="width:100%;height:55px;line-height: 55px;margin:0;padding:5px;background-color:#000000;vertical-align: middle">
        <img style="vertical-align:middle;margin-left:10px;" src="{{ $tplURL }}/img/batcow_yellow.png" width="79" height="50" alt="{{ $tplTitle }}" title="{{ $tplTitle }}" />
        <img style="vertical-align:middle;margin-left:10px;" src="{{ $tplURL }}/img/schokoladen_schrift_yellow.png" width="323" height="48" alt="{{ $tplTitle }}" title="{{ $tplTitle }}" />
    </div>
    @if($title)
        <h2>{{ $title }}</h2>
        <div>
            {{ $tplTitle }}, Ackerstrasse 169, 10115 Berlin, <a target="_blank" href="https://www.schokoladen-berlin.de" style="text-decoration: none; color: #a00;">Hompage</a>
        </div>
    @endif
    @if($header)
       <div class="myHeader">{{ $header }}</div>
    @endif
    @if($data && $data->count())
        <div class="myEvents">
        @foreach($data as $item)
            <div class="myEvent" style="margin-bottom: 20px;">
                <h4 style="margin: 10px 0 10px 0;">
                    {{ $item->getEventDate()->formatLocalized('%A %d.%m.%Y') }} {{ $item->getEventTime() }}
                    {{ $item->getTitle() }}
                    <span class="myCategory" style="color: #0080ff;">{{ $item->getCategory()->name }}</span>
                </h4>

            @if($item->getSubtitle())
                 <h5 style="margin: 10px 0 10px 0;">{{ $item->getSubtitle() }}</h5>
            @endif
            @if($item->getTheme())
                 <div class="myTheme" style="margin-bottom: 10px;">{{ $item->getTheme()->name }}</div>
            @endif

            @if($item->getDescription())
                 <div class="myText" style="margin-bottom: 10px;">{!! nl2br($item->getDescriptionText()) !!}</div>
            @endif
{{--
            @if($item->getLinks()->count())
                <div class="myLinks" style="margin-bottom: 10px;">
                @foreach($item->getLinks() as $link)
                    <a href="{{ $link }}" target="_blank" style="text-decoration: none; color: #a00;">
                        {{ $link }}</a>
                @endforeach
                </div>
            @endif
--}}
                <div class="myLinks" style="margin-bottom: 10px;">
                    <a href="{{ $tplURL }}/events/show/{{ $item->getEventDate()->format('Y-m-d') }}" target="_blank" style="text-decoration: none; color: #a00;">show on Page</a>
                </div>

            </div>
        @endforeach
        </div>
    @endif
</div>
