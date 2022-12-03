@extends('layouts.public')

@section('title', 'Kontakt für Bands')

@section('sidebar-left')
    @parent
@endsection

@section('content')
    <div class="row w-100">
        <div class="col-sm-12 col-lg-6">
            <x-form
                    method="post"
                    action="{{ route('public.message.store') }}"
                    class="w-100 mx-3"
            >
                <x-form-select name="music_style_id" label="Musik Richtung" :options="$musicStyles" required />
                <x-form-input name="name" label="Name" placeholder="Name" required />
                <x-form-input type="email" name="email" label="Email" placeholder="Email Adresse" required />
                <x-form-textarea rows="6" name="message" label="Deine Nachricht" placeholder="your message" required></x-form-textarea>
                <div class="form-group mt-4 mb-4">
                    <span class="block text-xl text-blue-900 mb-2">Captcha Text (zur Absicherung)</span>
                    <div class="captcha">
                        <span>{!! captcha_img('flat') !!}</span>
                        <button type="button" class="btn btn-danger inline-block" class="reload" id="reload">&#x21bb;</button>
                    </div>
                </div>
                <x-form-input id="captcha" name="captcha" label="Hier den darüber angezeigten Text eintragen" placeholder="Hier Captcha Text eintragen" />
                <x-form-submit name="submit" class="btn btn-save h-10 mt-3 w-full md:w-1/2">
                    Senden
                </x-form-submit>
            </x-form>
        </div>
    </div>

@endsection

@section('sidebar-right')
    @parent
@endsection

@section('inline-scripts')
    <script>
		const reloadURL = "{{ route('reload.captcha') }}";
		$('#reload').click(function () {
			$.ajax({
				type: 'GET',
				url: reloadURL,
				success: function (data) {
					$(".captcha span").html(data.captcha);
				}
			});
		});
    </script>
@endsection
