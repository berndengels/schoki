<?php

namespace App\Http\Requests;

class ThemeRequest extends AdminRequest
{
    public function validationData()
    {
        return array_merge(['is_published' => false], $this->all());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'          => 'required|min:3|max:50',
            'slug'          => '',
            'icon'          => '',
            'icon_orig'     => '',
            'default_time'  => '',
            'default_price' => '',
            'is_published'  => 'boolean',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Bitte den Namen eintragen!',
            'name.min'      => 'Der Name muß mindestens 3 Zeichen lang sein.',
            'name.max'      => 'Der Name darf maximal 160 Zeichen enhalten.',
        ];
    }
}
