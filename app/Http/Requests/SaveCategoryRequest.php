<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveCategoryRequest extends FormRequest
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

    protected function validationData()
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
            'id'            => '',
            'name'          => 'required|min:3|max:50',
            'slug'          => '',
            'icon'          => '',
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
