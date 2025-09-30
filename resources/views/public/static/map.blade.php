@extends('layouts.public')

@section('title', 'Location')

@section('extra-headers')
    <link rel="stylesheet" href="{{ mix('vendor/leaflet/leaflet.css') }}">
    <script src="{{ mix('vendor/leaflet/leaflet.js') }}"></script>
@endsection

@section('sidebar-left')
    @parent
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-4 mt-5">
                <h2 class="page-header">Adresse</h2>
                <div class="address">
                    <span>Ackerstrasse 169, 10115 Berlin</span><br>
                    <span>030 - 282 65 27</span><br>
                    <span><a href="mailto:info@schokoladen-mitte.de" target="_blank">info@schokoladen-mitte.de</a></span>
                </div>
            </div>
            <div class="col-lg-8 mt-5">
                <div id="map"></div>
            </div>
        </div>
    </div>
@endsection

@section('sidebar-right')
    @parent
@endsection

@section('inline-scripts')
<script>
    $(document).ready(() => {
        var lat = 52.529745,
            lng = 13.397245,
            location = [lat, lng],
            zoom = 16,
            map = L.map('map').setView(location, zoom),
            popup = L.popup()
                .setLatLng(location)
                .setContent('<p>Schokoladen Berlin-Mitte<br />Ackerstrasse 169</p>'),
            marker = L.marker(location)
                 .addTo(map)
                 .bindPopup(popup)
                 .openPopup()
        ;

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/" class="mbs">OpenStreetMap</a>'
        }).addTo(map);
    });
</script>
@endsection
