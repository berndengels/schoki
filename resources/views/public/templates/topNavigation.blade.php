<div id="top-navigation">
    <nav class="navbar fixed-top navbar-expand-lg navbar-dark- bg-white">
        <a class="navbar-brand blog-header-logo text-decoration-none" href="/">
            <img src="{{ asset('img/schokoladen-logo-spitting-cow-01.svg') }}" height="90" alt="Schokoladen">
        </a>
        <a class="pl-4 pr-4" href="/"><h1 class="mb-0">Schokoladen Mitte</h1></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#topNavbar" aria-controls="topNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="topNavbar">
            <ul class="navbar-nav mr-auto">
                @foreach ($topMenu as $item)
                       @if($item->children->count())
                            @foreach ($item->children as $child)
                            <li class="nav-item dropdown d-none">
                                <a class="nav-link" href="{{ $child->url }}">{{ $child->name }}</a>
                            </li>
                            @endforeach
                       @else
                        <li class="nav-item dropdown">
                            <a class="nav-link" href="{{ $item->url }}" @if('link' === $item->menuItemType->type) target="_blank" @endif aria-haspopup="false">{{ $item->name }}<span class="ml-2 sr-only">(current)</span></a>
                       @endif
                    </li>
                @endforeach
            </ul>
            <form class="d-none form-inline my-2 my-md-0">
                <input class="form-control" type="text" placeholder="Suchen">
            </form>
            <div>
                <p class="mb-0">Ackerstraße 169, 10115 Berlin-Mitte</p>
            </div>
        </div>


    </nav>
</div>



<div id="top-navigation" class="d-none">
    <nav class="navbar fixed-top navbar-expand-md navbar-dark bg-white">
        <a class="navbar-brand text-decoration-none" href="/events">
            <img src="{{ asset('img/schokologo.png') }}" height="90" alt="Schokoladen">
            <span class="text-uppercase- fs-2">Schokoladen Mitte</span>
            <img class="d-none" src="{{ asset('img/schokoladen_schrift_yellow.png') }}" width="323" height="48" alt="Schokoladen"></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#topNavbar" aria-controls="topNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

        <div class="collapse navbar-collapse" id="topNavbar">
            <ul class="navbar-nav mr-auto">
                @foreach ($topMenu as $item)
                    <li class="nav-item dropdown text-uppercase">
                        @if($item->children->count())
                            <a class="nav-link dropdown-toggle" href="{{ $item->url }}" id="dropdown{{ $item->name }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{ $item->name }}<span class="ml-2 sr-only">(current)</span></a>
                            <div class="dropdown-menu" aria-labelledby="dropdown{{ $item->name }}">
                                @foreach ($item->children as $child)
                                    <a class="dropdown-item" href="{{ $child->url }}">{{ $child->name }}</a>
                                @endforeach
                            </div>
                        @else
                            <a class="nav-link" href="{{ $item->url }}" @if('link' === $item->menuItemType->type) target="_blank" @endif aria-haspopup="false">{{ $item->name }}<span class="ml-2 sr-only">(current)</span></a>
                        @endif
                    </li>
                @endforeach
            </ul>
            <!--form class="form-inline my-2 my-md-0">
                <input class="form-control" type="text" placeholder="Suchen">
            </form-->
        </div>

        <div class="d-sm-none d-lg-block float-right my-auto mr-3">
            <p>Ackerstraße 169, 10115 Berlin-Mitte</p>
            <img class="d-none" src="{{ asset('img/power/address.png') }}" width="183" height="14" alt="Schokoladen, Ackerstraße 169, 10115 Berlin" title="Schokoladen, Ackerstraße 169, 10115 Berlin" />
        </div>
    </nav>
</div>