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

    protected function failedValidation(Validator $validator)
    {

        if($validator->failed()) {
//            dd($validator->errors());
            return redirect()->back()->with('errors', $validator->errors());
        }
/*
        if ( captcha_check($this->request->captcha) === false ) {
            return back()->with('invalid-captcha','incorrect captcha!');
        }
*/
        return parent::failedValidation($validator);
    }

    /**
     * Get the validation rules that apply to the request.
     * @return array
     */
    public function rules()
    {
        return [
            'music_style_id'    => 'required',
            'name'              => 'required|max:50',
            'email'             => 'required|email',
            'msg'               => 'required',
//            'captcha'           => 'required|captcha',
            'g-recaptcha-response' => ['required', new ReCaptcha()]
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
            'g-recaptcha-response.required'      => 'Bitte den Captcha-Text eingeben!',
            'g-recaptcha-response.captcha'       => 'Der Captcha-Text ":input" stimmt nicht!',
        ];
    }
}
