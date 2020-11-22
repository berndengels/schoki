<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveUserRequest extends FormRequest
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

    public function validationData()
    {
        return array_merge([
            'enabled'           => false,
            'is_super_admin'    => false
        ], $this->all());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = $this->request->get('id');
        return [
            'username'  => 'required|min:3|max:50',
            'email'     => (!$id) ? 'required|email|unique:my_user,email' : 'required|email',
            'password'  => (!$id) ? 'required|alpha_num|between:6,20' : 'nullable|alpha_num|between:6,20',
            'enabled'           => 'boolean',
            'is_super_admin'    => 'boolean',
            'music_style_id'    => '',
        ];
    }

    public function messages()
    {
        return [
            'username.required'     => 'Bitte einen Username eintragen!',
            'email.required'        => 'Bitte eine Email Adresse eintragen!',
            'email.eamil'           => 'Das ist keine korrekte Email-Adresse!.',
            'email.unique'          => 'Die angegebne Email-Adresse existiert bereits, bitte eine andere benutzen!.',
            'password.required'     => 'Bitte ein Passwort eingeben!',
            'password.alpha_num'    => 'Das Passwort darf nur alphanumerische Zeichen enthalten!',
            'password.between'      => 'Das Passwort muss mindestens 6 Zeichen und darf max. 20 Zeichen enthalten!',
        ];
    }
}
