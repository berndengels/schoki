<!doctype html>
<html lang="de">
<head>
    <title>Schokoladen Intern</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="pragma" content="no-cache" />
    <meta http-equiv="expires" content="0" />
    <link rel="preload" as="font">
    <link rel="icon" href="/favicons/favicon.ico" type="image/x-icon">
    <link rel="icon" type="image/png" href="/favicons/favicon-16x16.png" sizes="16x16">
    <link rel="icon" type="image/png" href="/favicons/favicon-32x32.png" sizes="32x32">
    <link rel="icon" type="image/png" href="/favicons/favicon-96x96.png" sizes="96x96">
    <script src="https://unpkg.com/ionicons@4.2.2/dist/ionicons.js"></script>
    <script src="{{ mix('js/app-admin.js') }}" type="text/javascript" charset="utf-8"></script>
    <link type="text/css" rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:200,600" />
    <link href="https://unpkg.com/ionicons@4.2.2/dist/css/ionicons.min.css" rel="stylesheet">
    <link type="text/css" rel="stylesheet" href="{{ mix('css/app-admin.css') }}" />
    @yield('extra-headers')
</head>
<body>

@section('navigation')
    @include('admin.navbar')
@show

<div class="content col-12">
    <div id="pageTitle" class="r-0 mr-3">
        <h1 class="d-inline-block text-info"><i class="ion-md-arrow-round-down mr-2"></i>{{ $title ?? null }}</h1>
    </div>
    @include('components.flash-message')
    @yield('content')
</div>

@yield('inline-scripts')

</body>
</html>
