<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

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
//            'captcha'       => 'required|captcha',
            'token'         => '',
            'remove'        => '',
        ];
    }

    public function messages()
    {
        return [
            'address_category_id.required' => 'Bitte die Kategorie der Adresse eintragen!',
            'email.required' => 'Bitte eine Email Adresse eintragen!',
            'email.email'    => 'Das ist keine korrekte Email-Adresse!.',
//            'captcha.required'  => 'Bitte das Captcha Feld ausfüllen.',
        ];
    }
}
