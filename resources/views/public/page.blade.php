@extends('layouts.public')

@section('title', $data->title)

@section('sidebar-left')
    @parent
@endsection

@section('content')
    <div class="page col-auto m-1">
        <h3 class="p-0">{{ $data->title }}</h3>
        <div class="page-body p-0 mbs">
            {!! $data->body !!}
        </div>
    </div>
@endsection

@section('sidebar-right')
    @parent
@endsection
