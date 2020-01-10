@extends('layouts.public')

@section('extra-headers')
    {!! NoCaptcha::renderJs() !!}
@endsection

@section('title', 'Kontakt für Bands')

@section('sidebar-left')
    @parent
@endsection

@section('content')
    {!! form_start($form) !!}
    {!! form_until($form, 'remove') !!}
    @if (isset($errors) && $errors->has('g-recaptcha-response'))
        <div class="captcha-error text-warning mx-auto">
            <!--b>{--!! $errors->first('g-recaptcha-response') !!--}</b-->
            <b>Bitte das Captcha bedienen.</b>
        </div>
    @endif
    {!! NoCaptcha::display(['class'=>'mbs']) !!}
    {!! form_end($form) !!}
@endsection

@section('sidebar-right')
    @parent
@endsection
