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

## Requirements
PHP 8.3+
Laravel 13+
Installation

## Install the package via Composer:

composer require shahid/laravel-captcha13

## Package Auto-Discovery

The package supports Laravel package auto-discovery.

So after installation, you do not need to manually register the service provider or alias.

## Publish Configuration

### If you want to customize the package configuration, publish the config file:

php artisan vendor:publish --tag=captcha13-config

This will publish:

config/captcha.php

## Basic Usage in Blade

You can render the captcha image in any Blade view like this:

### {!! Captcha::img() !!}

## Basic Form Example
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

## Validation Example (Controller)

 use Illuminate\Http\Request;</br>
 use Shahid\Captcha\Facades\Captcha;</br>

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

## Validation Example (Route Closure)
use Illuminate\Http\Request;</br>
use Illuminate\Support\Facades\Route;</br>
use Shahid\Captcha\Facades\Captcha;</br>

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

## Facade Usage

### The package provides a facade:

use Shahid\Captcha\Facades\Captcha;

## Available Methods
### Render captcha image HTML
Captcha::img();

## Validate captcha input
Captcha::validate($request->captcha);

## Configuration

After publishing the config file, you can customize the package using:

## config/captcha.php
Default Configuration

<?php

return [
    'length' => 6,</br>
    'width' => 200,</br>
    'height' => 60,</br>
    'background' => '#f9fafb',</br>
    'text_color' => '#374151',</br>
    'session_key' => 'captcha_code',</br>
    'route' => 'captcha/image',</br>
    'font' => null,</br>
];</br>


## Configuration Options

| Option | Description |
|--------|-------------|
| `length` | Number of characters in the captcha |
| `width` | Width of the captcha image |
| `height` | Height of the captcha image |
| `background` | Background color of the captcha image |
| `text_color` | Default text color (fallback if custom per-character colors are not used) |
| `session_key` | Session key used to store the captcha code |
| `route` | Route path used to serve the captcha image |
| `font` | Optional custom font path |

### Notes

- The package uses **session-based validation**, so the captcha image route must work under the `web` middleware.
- A new captcha is generated whenever the captcha image is refreshed.
- The package appends a timestamp query parameter to the image URL to prevent browser caching.
- If a custom font is not provided, the package uses its internal default font automatically.

## Recommended Package Route

If you want to understand how the package route works internally, it should use the `web` middleware because the package relies on sessions:

```php
Route::middleware('web')->group(function () {
    Route::get('captcha/image', [CaptchaController::class, 'image'])
        ->name('captcha13.image');
});
```
