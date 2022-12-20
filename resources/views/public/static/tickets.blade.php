@extends('layouts.public')

@section('title', 'Tickets')

@section('sidebar-left')
    @parent
@endsection

@section('content')
    <iframe
        src="https://schokoladen.tickettoaster.de"
        style="border: 0; width: 100%; max-width: 100%; height: 800px;"
    ></iframe>
@endsection

@section('sidebar-right')
    @parent
@endsection
