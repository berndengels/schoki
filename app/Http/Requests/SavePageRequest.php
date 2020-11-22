<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SavePageRequest extends FormRequest
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
            'title'         => 'required|string|min:3|max:160',
            'body'          => '',
            'is_published'  => 'boolean',
        ];
    }

    public function messages()
    {
        return [
            'title.required'    => 'Bitte den Titel eintragen!',
            'title.min'         => 'Der Titel muß mindestens 3 Zeichen lang sein.',
            'title.max'         => 'Der Titel darf maximal 160 Zeichen enhalten.',
        ];
    }
}
