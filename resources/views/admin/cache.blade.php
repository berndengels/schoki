
@extends('layouts.admin')

@section('content')
    <h3>Cache</h3>
    <div class="cache-result col-auto">
        <h5>{{ $action }}</h5>
        @if($success)
            <p class="text-success">war erfolgreich</p>
        @else
            <p class="text-danger">
                war leider nicht erfolgreich:
                <br>
                <p>{{ $error }}</p>
            </p>
        @endif
    </div>
@endsection
