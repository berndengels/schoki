<!--div id="bottom-navigation"-->
    <nav class="navbar fixed-bottom navbar-expand-sm navbar-dark bg-black p-0 m-0">
        <a class="navbar-brand" href="#"></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#bottomNavbar" aria-controls="bottomNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse m-0 p-0" id="bottomNavbar">
            <ul class="navbar-nav mr-auto p-0">
                @foreach ($bottomMenu as $item)
                <li class="nav-item p-0 m-0 @if($item->children->count()) dropup @endif">
                    @if($item->children->count())
                        <a class="nav-link dropup-toggle" href="{{ $item->url }}" id="dropup{{ $item->name }}" data-toggle="dropup" aria-haspopup="true" aria-expanded="false">{{ $item->name }}<span class="ml-2 sr-only">(current)</span></a>
                        <div class="dropup-menu" aria-labelledby="dropup{{ $item->name }}">
                            @foreach ($item->children as $child)
                            <a class="dropup-item" href="{{ $child->url }}">
                                @if('' !== $child->icon)
                                <img src="/img/icons/{{ $child->icon }}" title="{{ $child->icon }}" alt="{{ $child->icon }}">
                                @else
                                {{ $child->name }}
                                @endif
                            </a>
                            @endforeach
                        </div>
                    @else
                        <a class="nav-link p-0 mt-2 mr-3" href="{{ $item->url }}" aria-haspopup="false">
                            @if($item->icon)
                                @if(false === strrpos($item->icon,'.'))
                                    <ion-icon name="{{ $item->icon }}" title="{{ $item->icon }}"></ion-icon>
                                @else
                                    <img src="/img/icons/{{ $item->icon }}" title="{{ $item->icon }}" alt="{{ $item->icon }}">
                                @endif
                            @else
                                {{ $item->name }}<span class="ml-2 sr-only">(current)</span>
                            @endif
                        </a>
                    @endif
                </li>
                @endforeach
            </ul>
        </div>
    </nav>
<!--/div-->