<?php

namespace Shahid\Captcha\Http\Controllers;

use Illuminate\Routing\Controller;
use Shahid\Captcha\Facades\Captcha;

class CaptchaController extends Controller
{
    public function image()
    {
        $image = Captcha::generateImageBinary();

        return response($image, 200)
            ->header('Content-Type', 'image/png')
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
    }
}
