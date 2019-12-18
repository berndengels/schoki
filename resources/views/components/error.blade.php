
@extends('layouts.admin')

@section('content')
    @include('components.js-back')
    <h3 class="alert-title">Error</h3>
    <div class="alert-error">{{ $message }}</div>
    @include('components.js-back')
@endsection

