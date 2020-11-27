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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id'            => '',
            'address_category_id'   => 'required',
            'email'          => 'required|email',
            'token'          => '',
        ];
    }

    public function messages()
    {
        return [
            'address_category_id.required' => 'Bitte die Kategorie der Adresse eintragen!',
            'email.required' => 'Bitte eine Email Adresse eintragen!',
            'email.eamil'    => 'Das ist keine korrekte Email-Adresse!.',
        ];
    }
}
