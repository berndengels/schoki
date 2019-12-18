@extends('layouts.public')

@section('title', 'Mailversand')

@section('sidebar-left')
    @parent
@endsection

@section('content')
    <div class="col-auto mx-sm-1 mx-3">
        <h3>Mail wurde versand.</h3>
        <br>
        <div class="mail-content mbs">{!! nl2br($content) !!}</div>
    </div>
@endsection

@section('sidebar-right')
    @parent
@endsection
