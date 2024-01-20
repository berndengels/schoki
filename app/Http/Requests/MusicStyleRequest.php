<?php

namespace App\Http\Requests;

class MusicStyleRequest extends AdminRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'  => 'required',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Bitte einen Namen eintragen!',
        ];
    }
}
