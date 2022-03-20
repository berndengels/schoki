<?php

namespace App\Http\Controllers;

class CaptchaServiceController extends Controller
{
    public function reloadCaptcha()
    {
        return response()->json([
            'captcha'   => captcha_img('flat'),
        ]);
    }
}
