@extends('layouts.public')

@section('title', 'Kontakt für Bands')

@section('extra-headers')
    <script src="https://www.google.com/recaptcha/api.js?render={{ env('NOCAPTCHA_SITEKEY') }}"></script>
@endsection

@section('sidebar-left')
    @parent
@endsection

@section('content')
    <div class="row w-100 m-sm-2 m-md-3">
        <div class="col-sm-12 col-lg-6">
            <x-form
                    id="frmBandMessage"
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
                <x-form-submit
                        class="g-recaptcha btn btn-save float-left mt-3"
                        data-sitekey="{{ env('NOCAPTCHA_SITEKEY') }}"
                        data-callback='onSubmit'
                        data-action='submit'>
                    Senden
                </x-form-submit>
            </x-form>
        </div>
    </div>
@endsection

@section('inline-scripts')
<script>
    const fID = "#frmBandMessage";
	function onSubmit() {
		grecaptcha.ready(function() {
			grecaptcha.execute("{{ env('NOCAPTCHA_SITEKEY') }}", {action: 'submit'}).then(function(token) {
				$(fID).prepend('<input type="hidden" name="token" value="' + token + '">');
				$(fID).submit();
			});
		});
	}
</script>
@endsection

@section('sidebar-right')
    @parent
@endsection
