@extends('layouts.admin')
@section('title', 'PHP-Info')

@section('content')
    <div class="col-12 m-1">
        @php
        phpinfo();
        @endphp
    </div>
@endsection
