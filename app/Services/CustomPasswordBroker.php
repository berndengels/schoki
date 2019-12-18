<?php
namespace App\Services;

use Illuminate\Auth\Passwords\PasswordBroker as BasePasswordBroker;

class CustomPasswordBroker extends BasePasswordBroker
{
    private $minLen = 4;
    /**
     * Determine if the passwords are valid for the request.
     *
     * @param  array  $credentials
     * @return bool
     */
    protected function validatePasswordWithDefaults(array $credentials)
    {
        [$password, $confirm] = [
            $credentials['password'],
            $credentials['password_confirmation'],
        ];

        return $password === $confirm && mb_strlen($password) >= $this->minLen;
    }
}
