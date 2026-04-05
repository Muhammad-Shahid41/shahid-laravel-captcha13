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
```bash
composer require ms41/laravel-captcha13
```
## Package Auto-Discovery

The package supports Laravel package auto-discovery.

So after installation, you do not need to manually register the service provider or alias.

## Publish Configuration

### If you want to customize the package configuration, publish the config file:
```bash
php artisan vendor:publish --tag=captcha13-config
```
This will publish:

config/captcha.php

## Basic Usage in Blade

You can render the captcha image in any Blade view like this:
```blade
{!! Captcha::img() !!}
```

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
```php
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
```

## Validation Example (Route Closure)
```php
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
```
## Facade Usage

### The package provides a facade:
```php
use Shahid\Captcha\Facades\Captcha;
```
## Available Methods
### Render captcha image HTML
```blade
Captcha::img();
```

## Validate captcha input
```php
Captcha::validate($request->captcha);
```
## Configuration

After publishing the config file, you can customize the package using:

## config/captcha.php
Default Configuration
```php
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
```


## Configuration Options

### Notes

- The package uses **session-based validation**, so the captcha image route must work under the `web` middleware.</br>
- A new captcha is generated whenever the captcha image is refreshed.</br>
- The package appends a timestamp query parameter to the image URL to prevent browser caching.</br>
- If a custom font is not provided, the package uses its internal default font automatically.</br>

## Recommended Package Route

If you want to understand how the package route works internally, it should use the `web` middleware because the package relies on sessions:</br>
```php
Route::middleware('web')->group(function () {
    Route::get('captcha/image', [CaptchaController::class, 'image'])
        ->name('captcha13.image');
});
```

