<?php

use Illuminate\Support\Facades\Route;
use Shahid\Captcha\Http\Controllers\CaptchaController;

Route::middleware('web')->group(function () {
    Route::get('captcha/image', [CaptchaController::class, 'image'])
        ->name('captcha13.image');
});