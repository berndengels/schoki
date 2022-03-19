<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BandContactRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'music_style_id'    => 'required',
            'name'              => 'required',
            'email'             => 'required|email',
            'message'           => 'required',
//            'recaptcha'         => 'required',
            'captcha'           => 'required|captcha',
        ];
    }

    public function messages()
    {
        return [
            'music_style_id.required'   => 'Bitte eine Musik-Richtung angeben!',
            'name.required'         => 'Bitte einen Name angeben!',
            'email.required'        => 'Bitte eine Email Adresse angeben!',
            'email.email'           => 'Das ist keine korrekte Email-Adresse!.',
            'message.required'      => 'Bitte ein Nachricht eingeben!',
            'captcha.required'      => 'Bitte den Captcha-Text eingeben!',
            'captcha.captcha'       => 'Bitte den Captcha-Text stimmt nicht!',
        ];
    }
}
