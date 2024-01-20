<?php

namespace App\Http\Requests;

use App\Rules\ReCaptcha;

class AddressRequest extends AdminRequest
{
    public function validationData($keys = null)
    {
        return array_merge($this->all($keys), ['remove' => !!$this->post('remove') ?? false]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'address_category_id'   => 'required',
            'email'         => 'required|email',
            'token'         => '',
            'remove'        => '',
            'g-recaptcha-response' => ['required', new ReCaptcha()]
        ];
    }
/*
    public function messages()
    {
        return [
            'address_category_id.required' => 'Bitte die Kategorie der Adresse eintragen!',
            'email.required' => 'Bitte eine Email Adresse eintragen!',
            'email.email'    => 'Das ist keine korrekte Email-Adresse!.',
            'captcha.required'  => 'Bitte das Captcha Feld bedienen.',
        ];
    }
*/
}
