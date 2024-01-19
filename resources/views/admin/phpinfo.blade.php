@extends('layouts.admin')
@section('title', 'PHP-Info')

@section('extra-hedares', $style)
@section('content')
    <div id="phpinfo" class="m-sm-2 m-md-5">
        {!! $body !!}
    </div>
@endsection
