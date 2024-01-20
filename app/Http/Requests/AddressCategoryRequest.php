<?php

namespace App\Http\Requests;

class AddressCategoryRequest extends AdminRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id'            => '',
            'name'          => 'required',
            'tag_id'        => '',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Bitte den Namen eintragen!',
        ];
    }
}
