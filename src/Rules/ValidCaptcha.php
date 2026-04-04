<?php

namespace Shahid\Captcha\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Shahid\Captcha\Facades\Captcha;

class ValidCaptcha implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!Captcha::validate((string) $value)) {
            $fail('The captcha is incorrect.');
        }
    }
}
