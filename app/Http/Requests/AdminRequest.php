<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class AdminRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

	protected function passedValidation()
	{
		session()->remove('error');
	}

	protected function failedValidation(Validator $validator)
	{
		session()->put('error', $validator->errors()->first());
		parent::failedValidation($validator);
	}
}
