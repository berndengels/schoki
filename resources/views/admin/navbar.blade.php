
<nav class="navbar fixed-top navbar-expand-md navbar-dark bg-dark fixed-top border-bottom border-body">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">NavBar</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar" aria-controls="adminNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="adminNavbar">
            <ul class="navbar-nav me-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="dropdownDaten" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Daten <span class="sr-only">(current)</span></a>
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
                    <a class="nav-link dropdown-toggle" href="#" id="dropdownAccounts" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Accounts</a>
                    <div class="dropdown-menu" aria-labelledby="dropdownAccounts">
                        <a class="dropdown-item" href="/admin/users">Users</a>
                        @if( Auth::user() && 1 === Auth::user()->is_super_admin )
                            <a class="dropdown-item" href="/admin/users/reset">Reset Users</a>
                        @endif
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="dropdownBooking" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Booking</a>
                    <div class="dropdown-menu" aria-labelledby="dropdownBooking">
                        <a class="dropdown-item" href="/admin/musicStyles">Musik-Styles</a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="dropdownPost" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Post</a>
                    <div class="dropdown-menu" aria-labelledby="dropdownPost">
                        <a class="dropdown-item" href="/admin/messages">Band-Anfragen</a>
                        <a class="dropdown-item" href="/admin/addressCategories">AdressKategorien</a>
                        <a class="dropdown-item" href="/admin/addresses">Adressen</a>
                    </div>
                </li>
                <!--li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" href="#" id="dropdownNewsletter" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Newsletter</a>
					<div class="dropdown-menu" aria-labelledby="dropdownNewsletter">
						<a class="dropdown-item" href="/admin/newsletter">alle Newsletter</a>
						<a class="dropdown-item" href="/admin/newsletter/edit">Newsletter erstellen</a>
					</div>
				</li-->

                @if( Auth::user() && 1 === Auth::user()->is_super_admin )
                    {? $view = isset($view) ? $view : '' ?}
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="dropdownMedia" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Media</a>
                        <div class="dropdown-menu" aria-labelledby="dropdownMedia">
                            <a class="dropdown-item" href="{{ route('admin.service.dumpDb') }}" target="_blank">Download Database</a>
                            <a class="dropdown-item ajax" href="/admin/services/syncImages" target="_blank">Sync Images</a>
                            <a class="dropdown-item ajax" href="/admin/services/cropImages" target="_blank">Crop Images</a>
                            <a class="dropdown-item ajax" href="/admin/services/sanitizeImageDB" target="_blank">Sanitize Images DB</a>
                            <a class="dropdown-item ajax" href="/admin/services/syncAudios" target="_blank">Sync Audios</a>
                            <a class="dropdown-item ajax" href="/admin/services/sanitizeFilePermissions" target="_blank">Set Permissions</a>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="dropdownReports" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Reports</a>
                        <div class="dropdown-menu" aria-labelledby="dropdownReports">
                            <a class="dropdown-item" href="/admin/terminal/{{ $view }}">Terminal</a>
                            <a class="dropdown-item" href="/admin/services/browserTestReport">BrowserTest Result</a>
                            <a class="dropdown-item" href="/admin/phpinfo">PHP-Info</a>
                        </div>
                    </li>
                @endif

            </ul>
            <span class="float-end clearfix">
            <span class="text-info">{!! $untilValidDate->formatLocalized('%A') !!} {!! $untilValidDate->format('d.m.Y H:i') !!}</span>
            <a class="ml-2" href="/logout"><b>@if(auth()->user()) {{ auth()->user()->username }}@endif Logout</b><i class="ion-md-log-out ms-2"></i></a>
        </span>

            <!--form class="form-inline my-2 my-md-0">
				<input class="form-control" type="text" placeholder="Suchen">
			</form-->
            @if( isset($searchForm) )
                {!! form($searchForm) !!}
            @endif
        </div>
    </div>
</nav>
