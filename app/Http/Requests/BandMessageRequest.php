<?php

namespace App\Http\Requests;

use App\Rules\ReCaptcha;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Request;

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

    /**
     * Get the validation rules that apply to the request.
     * @return array
     */
    public function rules()
    {
        return [
            'music_style_id'    => 'required',
            'name'              => 'required|max:100',
            'email'             => 'required|email',
            'msg'               => 'required',
			'g-recaptcha-response' => 'recaptcha',
        ];
    }

    public function messages()
    {
        return [
            'music_style_id.required'   => 'Bitte eine Musik-Richtung angeben!',
            'name.required'         => 'Bitte einen Name angeben!',
            'name.max'              => 'Der Name darf max. 50 Zeichen enthalten!',
            'email.required'        => 'Bitte eine Email Adresse angeben!',
            'email.email'           => 'Das ist keine korrekte Email-Adresse!.',
            'msg.required'          => 'Bitte ein Nachricht eingeben!',
//            'g-recaptcha-response.required'      => 'Bitte den Captcha-Text eingeben!',
//            'g-recaptcha-response.captcha'       => 'Der Captcha-Text ":input" stimmt nicht!',
        ];
    }
}
