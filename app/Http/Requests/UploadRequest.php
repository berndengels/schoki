<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadRequest extends FormRequest
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
     *
     * @return array
     */
    public function rules()
    {
        return [
            'external_filename' => 'image|mimes:jpeg,jpg,gif,png|size:2048',
        ];
/*
        $files = count($this->input('external_filename'));
        foreach(range(0, $files) as $index) {
            $rules['external_filename.' . $index] = 'image|mimes:jpeg,jpg,gif,png|max:2048';
        }
*/
    }
}
