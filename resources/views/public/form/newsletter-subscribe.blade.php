@extends('layouts.public')

@section('title', 'Kontakt für Bands')

@section('sidebar-left')
    @parent
@endsection

@section('content')
    <x-form
            method="post"
            action="{{ route('public.newsletter.store') }}"
            class="w-full mx-3 md:w-1/2"
    >
        <x-form-select id="address_category_id" name="address_category_id" label="Adress-Kategorie" :options="$addressCategories" />
        <x-form-errors name="address_category_id" />
        <x-form-input type="text" id="email" name="email" label="Email" placeholder="Email Adresse" />
        <x-form-errors name="email" />
        <x-form-checkbox name="remove" label="Ich möchte den Newsletter abbestellen" />

        <!--div class="form-group mt-4 mb-4">
            <span class="block text-xl text-blue-900 mb-2">Captcha Text (zur Absicherung)</span>
            <div class="captcha">
                <span>
                    {!! captcha_img('flat') !!}
                </span>
                <button type="button" class="btn btn-danger inline-block" class="reload" id="reload">
                    &#x21bb;
                </button>
            </div>
        </div>
        <x-form-input id="captcha" name="captcha" label="Hier den darüber angezeigten Text eintragen" placeholder="Captcha Text eintragen"/-->

        <x-form-submit name="submit" class="btn btn-save h-10 mt-3 w-full md:w-1/2">
            Senden
        </x-form-submit>
    </x-form>
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

@section('sidebar-right')
    @parent
@endsection
