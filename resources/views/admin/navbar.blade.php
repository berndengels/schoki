
<nav class="navbar fixed-top navbar-expand-md navbar-dark bg-dark fixed-top">
    <a class="navbar-brand" href="#">NavBar</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#adminNavbar" aria-controls="adminNavbar" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="adminNavbar">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="dropdownDaten" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Daten <span class="sr-only">(current)</span></a>
                <div class="dropdown-menu" aria-labelledby="dropdownDaten">
                    <a class="dropdown-item" href="/admin/events">Events</a>
                    <a class="dropdown-item" href="/admin/eventsPeriodic">Periodische Events</a>
                    <a class="dropdown-item" href="/admin/eventsTemplate">Event Templates</a>
                    <a class="dropdown-item" href="/admin/categories">Kategorien</a>
                    <a class="dropdown-item" href="/admin/themes">Themen</a>
                    <a class="dropdown-item" href="/admin/pages">Pages</a>
                    <a class="dropdown-item" href="/admin/menus/show">Menu</a>
                    <a class="dropdown-item" href="/admin/events/archive">Archive</a>
                    <hr />
                    <a class="dropdown-item" href="/admin/cache/flush">Flush Cache</a>
                </div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="dropdownAccounts" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Accounts</a>
                <div class="dropdown-menu" aria-labelledby="dropdownAccounts">
                    <a class="dropdown-item" href="/admin/users">Users</a>
                    @if( Auth::user() && 1 === Auth::user()->is_super_admin )
                    <a class="dropdown-item" href="/admin/users/reset">Reset Users</a>
                    @endif
                </div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="dropdownBooking" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Booking</a>
                <div class="dropdown-menu" aria-labelledby="dropdownBooking">
                    <a class="dropdown-item" href="/admin/musicStyles">Musik-Styles</a>
                </div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="dropdownPost" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Post</a>
                <div class="dropdown-menu" aria-labelledby="dropdownPost">
                    <a class="dropdown-item" href="/admin/messages">Band-Anfragen</a>
                    <a class="dropdown-item" href="/admin/addressCategories">AdressKategorien</a>
                    <a class="dropdown-item" href="/admin/addresses">Adressen</a>
                </div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="dropdownNewsletter" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Newsletter</a>
                <div class="dropdown-menu" aria-labelledby="dropdownNewsletter">
                    <a class="dropdown-item" href="/admin/newsletter">alle Newsletter</a>
                    <a class="dropdown-item" href="/admin/newsletter/edit">Newsletter erstellen</a>
                </div>
            </li>

            @if( Auth::user() && 1 === Auth::user()->is_super_admin )
                {? $view = isset($view) ? $view : '' ?}
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="dropdownMedia" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Media</a>
                    <div class="dropdown-menu" aria-labelledby="dropdownMedia">
                        <!--a class="dropdown-item ajax" href="/admin/services/makeThumbs">Make Thumbs</a-->
                        <a class="dropdown-item ajax" href="/admin/services/syncImages">Sync Images</a>
                        <a class="dropdown-item ajax" href="/admin/services/cropImages">Crop Images</a>
                        <a class="dropdown-item ajax" href="/admin/services/sanitizeImageDB">Sanitize Images DB</a>
                        <a class="dropdown-item ajax" href="/admin/services/syncAudios">Sync Audios</a>
                        <a class="dropdown-item ajax" href="/admin/services/sanitizeFilePermissions">Set Permissions</a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="dropdownReports" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Reports</a>
                    <div class="dropdown-menu" aria-labelledby="dropdownReports">
                        <a class="dropdown-item" href="/admin/terminal/{{ $view }}">Terminal</a>
                        <a class="dropdown-item" href="/admin/services/browserTestReport">BrowserTest Result</a>
                    </div>
                </li>
            @endif

        </ul>
        <span class="float-right clearfix">
            <span class="text-info">{!! $untilValidDate->formatLocalized('%A') !!} {!! $untilValidDate->format('d.m.Y H:i') !!}</span>
            <a class="ml-2" href="/logout"><b>@if(auth()->user()) {{ auth()->user()->username }}@endif Logout</b><i class="ion-md-log-out ml-2"></i></a>
        </span>

        <!--form class="form-inline my-2 my-md-0">
            <input class="form-control" type="text" placeholder="Suchen">
        </form-->
        @if( isset($searchForm) )
            {!! form($searchForm) !!}
        @endif
    </div>
</nav>
