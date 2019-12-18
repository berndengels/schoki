@extends('layouts.admin')

@section('content')
    @include('components.newsletter-back')
    {!! dd($response) !!}
    @include('components.newsletter-back')
@endsection
