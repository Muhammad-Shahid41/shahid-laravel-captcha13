# Laravel Captcha 13

A simple, customizable, and Laravel 13 compatible CAPTCHA package for generating image-based captchas with session-based validation.

This package provides:

- CAPTCHA image generation
- Session-based validation
- Easy Blade integration
- Configurable size, route, colors, and font
- Laravel auto-discovery support

---

## Preview

### Captcha Example

> Add your preview image(s) here after uploading screenshots to your GitHub repository.

#### Example 1
![Captcha Preview 1](./images-preview/preview1.png)

#### Example 2
![Captcha Preview 2](./images-preview/preview2.png)

Requirements
PHP 8.3+
Laravel 13+
Installation

Install the package via Composer:

composer require shahid/laravel-captcha13

Package Auto-Discovery

The package supports Laravel package auto-discovery.

So after installation, you do not need to manually register the service provider or alias.

Publish Configuration

If you want to customize the package configuration, publish the config file:

php artisan vendor:publish --tag=captcha13-config

This will publish:

config/captcha.php

Basic Usage in Blade

You can render the captcha image in any Blade view like this:

{!! Captcha::img() !!}

Basic Form Example
<form method="POST" action="{{ route('captcha.submit') }}">
    @csrf

    <div>
        {!! Captcha::img() !!}
    </div>

    <div style="margin-top: 10px;">
        <input type="text" name="captcha" placeholder="Enter captcha">
    </div>

    <button type="submit" style="margin-top: 10px;">Submit</button>
</form>

Validation Example (Controller)
use Illuminate\Http\Request;
use Shahid\Captcha\Facades\Captcha;

public function submit(Request $request)
{
    $request->validate([
        'captcha' => ['required'],
    ]);

    if (!Captcha::validate($request->captcha)) {
        return back()
            ->withErrors(['captcha' => 'Invalid captcha entered.'])
            ->withInput();
    }

    return back()->with('success', 'Captcha verified successfully!');
}

Validation Example (Route Closure)
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Shahid\Captcha\Facades\Captcha;

Route::get('/captcha-test', function () {
    return view('captcha-test');
});

Route::post('/captcha-test', function (Request $request) {
    $request->validate([
        'captcha' => ['required'],
    ]);

    if (!Captcha::validate($request->captcha)) {
        return back()
            ->withErrors(['captcha' => 'Invalid captcha entered.'])
            ->withInput();
    }

    return back()->with('success', 'Captcha verified successfully!');
})->name('captcha.test.submit');

Full Blade Test Example

Create a test Blade file like:

resources/views/captcha-test.blade.php
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Captcha Test</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f3f4f6;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        .card {
            background: #ffffff;
            width: 100%;
            max-width: 420px;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        }

        h2 {
            margin-top: 0;
            margin-bottom: 20px;
            text-align: center;
            color: #111827;
        }

        .captcha-box {
            margin-bottom: 15px;
            text-align: center;
        }

        .captcha-box img {
            border: 1px solid #d1d5db;
            border-radius: 6px;
            padding: 5px;
            background: #fff;
        }

        input[type="text"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            margin-bottom: 15px;
            font-size: 15px;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 12px;
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            cursor: pointer;
        }

        button:hover {
            background: #1d4ed8;
        }

        .message {
            margin-bottom: 15px;
            padding: 10px 12px;
            border-radius: 8px;
            font-size: 14px;
        }

        .success {
            background: #dcfce7;
            color: #166534;
        }

        .error {
            background: #fee2e2;
            color: #991b1b;
        }
    </style>
</head>
<body>

    <div class="card">
        <h2>Captcha Package Test</h2>

        @if (session('success'))
            <div class="message success">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->has('captcha'))
            <div class="message error">
                {{ $errors->first('captcha') }}
            </div>
        @endif

        <form method="POST" action="{{ route('captcha.test.submit') }}">
            @csrf

            <div class="captcha-box">
                {!! Captcha::img() !!}
            </div>

            <input 
                type="text" 
                name="captcha" 
                placeholder="Enter captcha"
                value="{{ old('captcha') }}"
            >

            <button type="submit">Verify Captcha</button>
        </form>
    </div>

</body>
</html>

Refresh Captcha Example

You can refresh the captcha image without reloading the page:

<div class="captcha-box">
    {!! Captcha::img() !!}
    <button type="button" onclick="refreshCaptcha()">Refresh Captcha</button>
</div>

<script>
    function refreshCaptcha() {
        const captchaImage = document.querySelector('.captcha-box img');
        if (captchaImage) {
            captchaImage.src = "{{ url('captcha/image') }}?t=" + new Date().getTime();
        }
    }
</script>

Facade Usage

The package provides a facade:

use Shahid\Captcha\Facades\Captcha;
Available Methods
Render captcha image HTML
Captcha::img();
Validate captcha input
Captcha::validate($request->captcha);
Configuration

After publishing the config file, you can customize the package using:

config/captcha.php
Default Configuration
<?php

return [
    'length' => 6,
    'width' => 200,
    'height' => 60,
    'background' => '#f9fafb',
    'text_color' => '#374151',
    'session_key' => 'captcha_code',
    'route' => 'captcha/image',
    'font' => null,
];
Configuration Options
Option	Description
length	Number of characters in the captcha
width	Width of the captcha image
height	Height of the captcha image
background	Background color of the captcha image
text_color	Default text color (fallback if custom per-character colors are not used)
session_key	Session key used to store the captcha code
route	Route path used to serve the captcha image
font	Optional custom font path
Notes
The package uses session-based validation, so the captcha image route must work under the web middleware.
A new captcha is generated whenever the captcha image is refreshed.
The package appends a timestamp query parameter to the image URL to prevent browser caching.
If a custom font is not provided, the package should use its internal default font.

Recommended Package Route

If you want to understand how the package route works internally, it should use the web middleware because the package relies on sessions:

Route::middleware('web')->group(function () {
    Route::get('captcha/image', [CaptchaController::class, 'image'])
        ->name('captcha13.image');
});
