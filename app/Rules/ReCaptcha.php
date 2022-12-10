<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Http;

class ReCaptcha implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $response = Http::post('https://www.google.com/recaptcha/api/siteverify',[
            'secret'    => env('NOCAPTCHA_SECRET'),
            'response'  => $value
        ]);
        if($response) {
//            $token = request('token');
            // @todo: handle missing-input-secret
            $json = $response->json();
            return $json['success'] ?? false;
        }
        return false;
    }
    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
//        return 'The google recaptcha is required.';
        return [
            'g-recaptcha-response.required' => 'Captcha wird benötigt!',
            'g-recaptcha-response.missing-input-secret' => 'Captcha Secret fehlt!',
        ];
    }
}
