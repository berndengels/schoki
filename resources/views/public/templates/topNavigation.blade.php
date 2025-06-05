<div id="top-navigation" class="container-fluid">
    <nav class="navbar fixed-top navbar-expand-lg navbar-dark- bg-white">
        <a class="navbar-brand blog-header-logo text-decoration-none" href="/">
            <img src="{{ asset('img/schokoladen-logo-spitting-cow-01.svg') }}" height="90" alt="Schokoladen">
        </a>
        <a class="pl-4 pr-4" href="/"><h1 class="mb-0">Schokoladen Mitte</h1></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#topNavbar" aria-controls="topNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="topNavbar">
            <ul class="navbar-nav me-auto">
                @foreach ($topMenu as $item)
                       @if($item->children->count())
                            @foreach ($item->children as $child)
                            <li class="nav-item dropdown">
                                <a class="nav-link" href="{{ $child->url }}">{{ $child->name }}</a>
                            </li>
                            @endforeach
                       @else
                            <li class="nav-item dropdown">
                                <a class="nav-link" href="{{ $item->url }}" @if('link' === $item->menuItemType->type) target="_blank" @endif aria-haspopup="false">{{ $item->name }}<span class="mS-2 sr-only">(current)</span></a>
                            </li>
                       @endif
                @endforeach
            </ul>
            <!--form class="d-none form-inline my-2 my-md-0">
                <input class="form-control" type="text" placeholder="Suchen">
            </form-->
            <div>
                <p class="mb-0">Ackerstraße 169, 10115 Berlin-Mitte</p>
            </div>
        </div>
    </nav>
</div>
