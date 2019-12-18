<div>
    <h5>Band Anfrage</h5>
    von {{ $name }} <a href="mailto:{{ $email }}" target="_blank">{{ $email }}</a> ({{ $created_at }})<br>
    Musikrichtung: {{ $musicStyle }}<br>
    Nachricht:<br>
    {!! nl2br($text) !!}
</div>