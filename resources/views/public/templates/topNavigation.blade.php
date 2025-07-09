<div id="top-navigation" class="container-fluid-">
    <nav class="navbar bg-white navbar-expand-md">
        <a class="navbar-brand blog-header-logo text-decoration-none" href="/">
            <img src="{{ asset('img/schokoladen-logo-spitting-cow-01.svg') }}" height="90" alt="Schokoladen">
        </a>
        <a class="ps-4 pe-4" href="/"><h1 class="mb-0">Schokoladen Mitte</h1></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#topNavbar" aria-controls="topNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="topNavbar">
            <ul class="navbar-nav me-auto">
                @foreach ($topMenu as $item)
                    @if($item->children->count() > 0)
                        <a class="nav-link dropdown-toggle" href="{{ $item->url }}" id="dropdown{{ $item->id }}" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{ $item->name }}<span class="ms-2 sr-only">(current)</span></a>
                        <div class="dropdown-menu" aria-labelledby="dropdown{{ $item->id }}">
                            @foreach ($item->children as $child)
                                <a class="dropdown-item" href="{{ $child->url }}">{{ $child->name }}</a>
                            @endforeach
                        </div>
                    @else
                        <a class="nav-link" href="{{ $item->url }}" @if('link' === $item->menuItemType->type) target="_blank" @endif aria-haspopup="false">{{ $item->name }}<span class="ms-2 sr-only">(current)</span></a>
                    @endif
                @endforeach
            </ul>
            <div>
                <p class="mb-0">Ackerstraße 169, 10115 Berlin-Mitte</p>
            </div>
        </div>
    </nav>
</div>

<!--div id="top-navigation">
    <nav class="navbar fixed-top navbar-expand-md navbar-dark bg-black">
        <a class="navbar-brand" href="/events">
            <img src="{{ asset('img/batcow_yellow.png') }}" width="79" height="50" alt="Schokoladen">
            <img src="{{ asset('img/schokoladen_schrift_yellow.png') }}" width="323" height="48" alt="Schokoladen"></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#topNavbar" aria-controls="topNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="topNavbar">
            <ul class="navbar-nav me-auto">
                @foreach ($topMenu as $item)
                    <li class="nav-item dropdown">
                        @if($item->children->count())
                            <a class="nav-link dropdown-toggle" href="{{ $item->url }}" id="dropdown{{ $item->name }}" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{ $item->name }}<span class="ms-2 sr-only">(current)</span></a>
                            <div class="dropdown-menu" aria-labelledby="dropdown{{ $item->name }}">
                                @foreach ($item->children as $child)
                                    <a class="dropdown-item" href="{{ $child->url }}">{{ $child->name }}</a>
                                @endforeach
                            </div>
                        @else
                            <a class="nav-link" href="{{ $item->url }}" @if('link' === $item->menuItemType->type) target="_blank" @endif aria-haspopup="false">{{ $item->name }}<span class="ms-2 sr-only">(current)</span></a>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="d-sm-none d-lg-block float-end my-auto me-3">
            <img src="{{ asset('img/power/address.png') }}" width="183" height="14" alt="Schokoladen, Ackerstraße 169, 10115 Berlin" title="Schokoladen, Ackerstraße 169, 10115 Berlin" />
        </div>
    </nav>
</div-->
