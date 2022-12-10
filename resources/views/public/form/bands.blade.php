@extends('layouts.public')

@section('title', 'Kontakt für Bands')

@section('extra-headers')
    {!! htmlScriptTagJsApi() !!}
@endsection

@section('sidebar-left')
    @parent
@endsection

@section('content')
    <div class="row w-100 m-sm-2 m-md-3">
        <div class="col-sm-12 col-lg-6">
            <x-form
                    id="frmBandMessage"
                    name="frmBandMessage"
                    method="post"
                    action="{{ route('public.message.store') }}"
                    class="w-100 mx-3"
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
                <x-form-select name="music_style_id" label="Musik Richtung" :options="$musicStyles"
                               default="{{ old('music_style_id') }}"/>
                <x-form-input name="name" label="Name" placeholder="Name" default="{{ old('name') }}"/>
                <x-form-input type="email" name="email" label="Email" placeholder="Email Adresse"
                              default="{{ old('email') }}"/>
                <x-form-textarea rows="6" name="msg" label="Deine Nachricht" placeholder="your message"
                                 default="{{ old('msg') }}"></x-form-textarea>
                <x-form-submit class="btn btn-save float-left mt-3">Senden</x-form-submit>
            </x-form>
        </div>
    </div>
@endsection

@section('inline-scripts')
<script>
</script>
@endsection

@section('sidebar-right')
    @parent
@endsection
