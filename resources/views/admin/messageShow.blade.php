
@extends('layouts.admin')

@section('content')
    @include('components.back')
    <h3>{{ $data->name }} {{ $data->created_at->format('d.m.Y H:i') }}</h3>
    <h6>
        <a href="mailto:{{ $data->email }}" target="_blank">{{ $data->email }}</a>
    </h6>

    @if($data->musicStyle)
        <h6>
           Musik-Richtung: {{ $data->musicStyle->name }}
        </h6>
    @endif
    <div class="massage">
        {!! nl2br($data->message) !!}
    </div>
    @include('components.back')
@endsection
