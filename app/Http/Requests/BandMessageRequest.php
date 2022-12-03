<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class BandMessageRequest extends FormRequest
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

    protected function failedValidation(Validator $validator)
    {
        if($validator->fails()) {
            dd($validator->errors());
        }
        parent::failedValidation($validator);
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
            'captcha'           => 'required|captcha'
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
            'captcha.captcha'       => 'Der Captcha-Text ":input" stimmt nicht!',
        ];
    }
}
