<div id="top-navigation">
    <nav class="navbar fixed-top navbar-expand-md navbar-dark bg-black">
        <a class="navbar-brand" href="/events">
            <img src="{{ asset('img/batcow_yellow.png') }}" width="79" height="50" alt="Schokoladen">
            <img src="{{ asset('img/schokoladen_schrift_yellow.png') }}" width="323" height="48" alt="Schokoladen"></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#topNavbar" aria-controls="topNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="topNavbar">
            <ul class="navbar-nav mr-auto">
                @foreach ($topMenu as $item)
                    <li class="nav-item dropdown">
                        @if($item->children->count())
                            <a class="nav-link dropdown-toggle" href="{{ $item->url }}" id="dropdown{{ $item->name }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{ $item->name }}<span class="ml-2 sr-only">(current)</span></a>
                            <div class="dropdown-menu" aria-labelledby="dropdown{{ $item->name }}">
                                @foreach ($item->children as $child)
                                    <a class="dropdown-item" href="{{ $child->url }}">{{ $child->name }}</a>
                                @endforeach
                            </div>
                        @else
                            <a class="nav-link" href="{{ $item->url }}" aria-haspopup="false">{{ $item->name }}<span class="ml-2 sr-only">(current)</span></a>
                        @endif
                    </li>
                @endforeach
            </ul>
            <!--form class="form-inline my-2 my-md-0">
                <input class="form-control" type="text" placeholder="Suchen">
            </form-->
        </div>

        <div class="d-sm-none d-lg-block float-right my-auto mr-3">
            <img src="{{ asset('img/power/address.png') }}" width="183" height="14" alt="Schokoladen, Ackerstraße 169, 10115 Berlin" title="Schokoladen, Ackerstraße 169, 10115 Berlin" />
        </div>
    </nav>
</div>
