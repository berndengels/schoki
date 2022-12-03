@extends('layouts.public')

@section('title', 'Kontakt für Bands')

@section('extra-headers')
    <script src="https://www.google.com/recaptcha/api.js?render={{ env('NOCAPTCHA_SITEKEY') }}"></script>
@endsection

@section('sidebar-left')
    @parent
@endsection

@section('content')
    <div class="row">
        <x-form
                id="frmNewsletter"
                method="post"
                action="{{ route('public.newsletter.store') }}"
                class="col-sm-12 col-lg-6 m-2"
        >
            <x-form-select id="address_category_id" name="address_category_id" label="Adress-Kategorie" :options="$addressCategories" />
            <x-form-input type="text" id="email" name="email" label="Email" placeholder="Email Adresse" />
            <x-form-checkbox name="remove" label="Ich möchte den Newsletter abbestellen" />
            <x-form-submit
                    class="g-recaptcha btn btn-save float-left mt-3"
                    data-sitekey="{{ env('NOCAPTCHA_SITEKEY') }}"
                    data-callback='onSubmit'
                    data-action='submit'>
                Senden
            </x-form-submit>
        </x-form>
    </div>
@endsection

@section('inline-scripts')
<script>
	const fID = "#frmNewsletter";
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
