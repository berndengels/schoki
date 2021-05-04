@extends('layouts.public')

@section('title', __('Wir haben geschlossen'))
@section('sidebarLeft', '')
@section('content')
    <div class="container mx-4">
        <div class="row">
            <h5>Aufgrund der aktuellen Lage bleiben wir geschlossen.<br>Passt auf Euch auf und bleibt gesund!</h5>
        </div>
        <div class="row">
            <p>-------------------------------------------------</p>
        </div>
        <div class="row">
            <h5>due to the current situation we'll also stay closed.<br>we'll try and re-schedule all cancelled shows as soon as possible.<br>stay healthy and safe everyone.</h5>
        </div>
        <div class="row">
            <p>-------------------------------------------------</p>
        </div>
        <div class="row">
            <div class="">
                <p>
                    Die Folgen der Coronakrise sind auch für uns dramatisch: keine Live-Musik, keine Gäste,
                    keine Einnahmen, aber jede Menge Kosten.
                    <br>
                    Du kannst einen Teil von Berlins Musikkultur retten,
                    indem Du den Schokoladen über Paypal unterstützt:
                </p>
                <p>
                    The consequences of the corona crisis are dramatic for us aswell: no live-music, no guests, no income but lots of costs to cover.
                    <br>
                    You can save a part of Berlins music culture by supporting Schokoladen via Paypal:
                </p>
                <form id="frmDonate" action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
                    <input type="hidden" name="cmd" value="_s-xclick" />
                    <input type="hidden" name="hosted_button_id" value="JYPGKFVHLT6YY" />
                    <label for="frmImage">Please</label>
                    <input id="frmImage" type="image" src="https://www.paypalobjects.com/en_US/DK/i/btn/btn_donateCC_LG.gif" border="0" name="submit" title="PayPal - The safer, easier way to pay online!" alt="Donate with PayPal button" />
                    <img alt="" border="0" src="https://www.paypal.com/en_DE/i/scr/pixel.gif" width="1" height="1" />
                </form>
                <div class="row">
                    <p>-------------------------------------------------</p>
                </div>
                <p>
                    oder alternativ per Überweisung:<br>
                    Schokoladen e.V.<br>
                    IBAN: DE39 1005 0000 0370 0061 43<br>
                    Verwendungszweck: Viva Schokoladen<br>
                    <br>
                    Wir danken allen Freunden von ganzem Herzen. Viva Schokoladen!
                </p>
                <div class="row">
                    <p>-------------------------------------------------</p>
                </div>
                <p>
                    or alternatively via invoice:<br>
                    Schokoladen e.V.<br>
                    IBAN: DE39 1005 0000 0370 0061 43<br>
                    intended purpose: Viva Schokoladen<br>
                    <br>
                    We'd like thank all friends from the bottom of our hearts. Viva Schokoladen!
                </p>
            </div>
        </div>
    </div>
@endsection
@section('sidebarRight', '')
