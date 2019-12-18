@extends('layouts.admin')

@section('content')
    @include('components.newsletter-back')
    <pre>{!! print_r(session()->all(),true) !!}</pre>
    <pre>{!! print_r($response,true) !!}</pre>
    @include('components.newsletter-back')
@endsection
