@extends('layouts.public')

@section('title', __('Wir haben geschlossen'))
@section('sidebarLeft', '')
@section('content')
    <div class="container">
        <div class="row">
            <h5>Aufgrund der aktuellen Lage bleiben wir geschlossen.<br>Passt auf Euch auf und bleibt gesund!</h5>
        </div>
        <div class="row">
            <p>---------------------------------------------------------------------------------</p>
        </div>
        <div class="row">
            <h5>due to the current situation we'll also stay closed.<br>we'll try and re-schedule all cancelled shows as soon as possible.<br>stay healthy and safe everyone.</h5>
        </div>
        <div class="row">
            <p>---------------------------------------------------------------------------------</p>
        </div>
        <div class="row">
            <div class="">
                <p>
                    Die Folgen der Koronakrise für den Schokoladen sind dramatisch: keine Live-Musik, keine Gäste, keine Einkommen. Du kannst einen Teil von Berlins Live-Musikkultur retten, indem Du den Schokoladen über Paypal unterstützt: spenden@schokoladen-mitte.de
                    Wir danken allen Freunden von ganzem Herzen. Viva Schokoladen!
                </p>
                <p>
                    The consequences of the corona crisis for  Schokoalden are dramatic: no live-music, no guests, no incomes. You can save a part of Berlins live music culture by supporting Schokoladen via Paypal: spenden@schokoladen-mitte.de.
                    We'd like thank all friends from the bottom of our hearts. Viva Schokoladen!
                </p>
                <form id="frmDonate" action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
                    <input type="hidden" name="cmd" value="_s-xclick" />
                    <input type="hidden" name="hosted_button_id" value="JYPGKFVHLT6YY" />
                    <label for="frmImage">Please</label>
                    <input id="frmImage" type="image" src="https://www.paypalobjects.com/en_US/DK/i/btn/btn_donateCC_LG.gif" border="0" name="submit" title="PayPal - The safer, easier way to pay online!" alt="Donate with PayPal button" />
                    <img alt="" border="0" src="https://www.paypal.com/en_DE/i/scr/pixel.gif" width="1" height="1" />
                </form>
            </div>
        </div>
    </div>
@endsection
@section('sidebarRight', '')
