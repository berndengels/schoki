
@extends('layouts.admin')

@section('title', $title)

@section('extra-headers')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.3.0/ekko-lightbox.css" rel="stylesheet" type="text/css">
    <!--script src="https://ajax.googleapis.com/ajax/libs/jquery/3.0.0/jquery.min.js" integrity="sha384-THPy051/pYDQGanwU6poAc/hOdQxjnOEXzbT+OuUAFqNqFjL+4IGLBgCJC3ZOShY" crossorigin="anonymous"></script-->
@endsection

@section('content')
    <div class="report col-12 m-1">
        @if($images->count())
            <h3>Dusk Report von  Tests</h3>
            <div class="reportWrapper">
            @foreach($images as $index => $img)
                {? $name = basename($img, '.jpg') ?}
                <div class="reportItem m-auto m-lg-2">
                    <a href="{{ asset('reports/images/'.$img) }}?image={{ $index }}" data-title="{{ $name }}" data-toggle="lightbox" data-type="image">
                        <img class="img-fluid" src="{{ asset('reports/images/thumbs/'.$img) }}?image={{ $index }}" title="{{ $name }}" alt="{{ $name }}">
                    </a>
                    <br>
                    <span>{{ $name }}</span>
                </div>
            @endforeach
            </div>    
        @else
            <h3>Keine Test-Daten vorhanden</h3>
        @endif
    </div>
@endsection

@section('inline-scripts')
<script>
$(document).on('click', '[data-toggle="lightbox"]', function(event) {
    event.preventDefault();
    $(this).ekkoLightbox();
});
</script>
@endsection
