@extends('layouts.public')

@section('title', 'Booking')

@section('extra-headers')
    {!! htmlScriptTagJsApi() !!}
@endsection

@section('sidebar-left')
    @parent
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-6 mt-5">
                <h2 class="page-header">Booking Anfragen</h2>
                <x-form
                    name="frm"
                    method="post"
                    action="{{ route('public.message.store') }}"
                    class="w-100 mt-4 booking-form"
                >
                    @if($errors)
                        <div class="row alert alert-danger w-100">
                            <ul>
                                @foreach ($errors as $name => $error)
                                    <li>{{ $error[0] }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <x-form-select name="music_style_id" label="Musik Richtung" :options="$musicStyles" default="{{ old('music_style_id') }}" />
                    <x-form-input name="name" label="Name" placeholder="Name" default="{{ old('name') }}" />
                    <x-form-input type="email" name="email" label="Email" placeholder="Email Adresse" default="{{ old('email') }}" />
                    <x-form-textarea rows="6" name="msg" label="Deine Nachricht" placeholder="your message" default="{{ old('msg') }}" />
                    <x-form-submit class="pill-btn mt-3">Senden</x-form-submit>
                </x-form>
            </div>
        </div>
    </div>
@endsection

@section('sidebar-right')
    @parent
@endsection
