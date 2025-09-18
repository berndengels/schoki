<div id="bottom-navigation">
    <nav class="navbar fixed-bottom navbar-expand navbar-dark bg-black px-2 px-lg-4">

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#bottomNavbar" aria-controls="bottomNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse m-0" id="bottomNavbar">
            <ul class="navbar-nav align-items-center me-auto p-0">
                @foreach ($bottomMenu as $item)
                <li class="nav-item p-0 m-0 @if($item->children->count()) dropup @endif">
                    @if($item->children->count())
                        <a class="nav-link dropup-toggle" href="{{ $item->url }}" id="dropup{{ $item->name }}" data-bs-toggle="dropup" aria-haspopup="true" aria-expanded="false">{{ $item->name }}<span class="ml-2 sr-only">(current)</span></a>
                        <div class="dropup-menu" aria-labelledby="dropup{{ $item->name }}">
                            @foreach ($item->children as $child)
                            <a class="dropup-item" href="{{ $child->url }}">
                                @if('' !== $child->icon)
                                <img src="/img/icons/{{ $child->icon }}" title="{{ $child->icon }}" alt="{{ $child->icon }}">
                                @elseif($child->fa_icon)
                                    <i class="{{ $child->fa_icon }}" title="{{ $child->name }}"></i>
                                @else
                                {{ $child->name }}
                                @endif
                            </a>
                            @endforeach
                        </div>
                    @else
                        <a class="nav-link" href="{{ $item->url }}" aria-haspopup="false" @if('link' === $item->menuItemType->type) target="_blank" @endif>
                            @if($item->icon)
                                @if(false === strrpos($item->icon,'.'))
                                    <ion-icon name="{{ $item->icon }}" title="{{ $item->name }}"></ion-icon>
                                @else
                                    <img src="/img/icons/{{ $item->icon }}" title="{{ $item->name }}" alt="{{ $item->name }}">
                                @endif
                            @elseif($item->fa_icon)
                                <i class="icn {{ $item->fa_icon }}" title="{{ $item->name }}"></i>
                            @else
                                {{ $item->name }}<span class="ms-2 sr-only">(current)</span>
                            @endif
                        </a>
                    @endif
                </li>
                @endforeach
            </ul>
            <span class="d-none d-sm-block navbar-text">Ackerstraße 169, 10115 Berlin-Mitte</span>
        </div>
    </nav>
</div>
