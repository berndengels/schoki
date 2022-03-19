@extends('layouts.public')

@section('title', 'Newsletter Registrierung')

@section('sidebar-left')
    @parent
@endsection

@section('content')
    <div class="col-auto mx-sm-1 mx-3 mbs">
        <h3>Newsletter Registrierung</h3>
        <div class="m-5">
            <p>{{ $message }}</p>
        </div>
    </div>
@endsection

@section('sidebar-right')
    @parent
@endsection
