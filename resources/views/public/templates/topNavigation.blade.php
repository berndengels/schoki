@php /*
 <div class="container">
    <header class="d-flex flex-wrap justify-content-center py-3 mb-4 border-bottom"><a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-body-emphasis text-decoration-none">
            <svg class="bi me-2" width="40" height="32" aria-hidden="true">
                <use xlink:href="#bootstrap"></use>
            </svg>
            <span class="fs-4">Simple header</span> </a>
        <ul class="nav nav-pills">
            <li class="nav-item"><a href="#" class="nav-link active" aria-current="page">Home</a></li>
            <li class="nav-item"><a href="#" class="nav-link">Features</a></li>
            <li class="nav-item"><a href="#" class="nav-link">Pricing</a></li>
            <li class="nav-item"><a href="#" class="nav-link">FAQs</a></li>
            <li class="nav-item"><a href="#" class="nav-link">About</a></li>
        </ul>
    </header>
</div>
 */
@endphp

<div id="top-navigation" class="container">
    <nav class="navbar bg-white navbar-expand-md">
        <a class="navbar-brand blog-header-logo text-decoration-none" href="/">
            <img src="{{ asset('img/schokoladen-logo-spitting-cow-01.svg') }}" height="90" alt="Schokoladen">
        </a>
        <a class="ps-4 pe-4" href="/"><h1 class="m-0">Schokoladen Mitte</h1></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#topNavbar" aria-controls="topNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-end" id="topNavbar">
            <ul class="navbar-nav">
                <a class="nav-link" data-bs-toggle="offcanvas" href="#offcanvasExample" role="button" aria-controls="offcanvasExample">Calendar</a>
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
        </div>
    </nav>
</div>
