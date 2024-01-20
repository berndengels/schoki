<?php

namespace App\Http\Requests;

class UploadRequest extends AdminRequest
{
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
