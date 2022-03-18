@extends('layouts.admin')

@section('content')
    @include('components.newsletter-back-list')
    @if(isset($response['persist']) && $response['persist'])
        <div class="row">
            <h3 class="m-5 text-primary">Newsletter erfolgreich angelegt</h3>
        </div>
    @endif
@endsection
