<?php

namespace Shahid\Captcha;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class CaptchaManager
{
    protected array $config;

    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

   public function generateCode(int $length = null): string
{
    $length = $length ?? ($this->config['length'] ?? 6);

    $characters = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz23456789';
    $code = '';

    for ($i = 0; $i < $length; $i++) {
        $code .= $characters[random_int(0, strlen($characters) - 1)];
    }

    session()->put($this->config['session_key'] ?? 'captcha_code', $code);

    return $code;
}

    public function getCode(): ?string
    {
        return Session::get($this->config['session_key'] ?? 'captcha_code');
    }

    public function validate(string $input): bool
    {
        $stored = $this->getCode();

        if (!$stored) {
            return false;
        }

        return ($stored) === trim($input);
    }

    public function generateImageBinary(): string
    {
        $width = $this->config['width'] ?? 160;
        $height = $this->config['height'] ?? 50;
        $bgColor = $this->config['background'] ?? '#f3f4f6';
        $textColor = $this->config['text_color'] ?? '#111827';

        $code = $this->generateCode();

        $manager = new ImageManager(new Driver());

        $image = $manager->create($width, $height)->fill($bgColor);

        for ($i = 0; $i < 5; $i++) {
             $image->drawLine(function ($draw) use ($width, $height) {
                $draw->from(rand(0, $width), rand(0, $height));
                $draw->to(rand(0, $width), rand(0, $height));
                $draw->color('#d1d5db');
                $draw->width(1);
            });
        }

        $fontPath = $this->config['font'] ?: __DIR__ . '/../resources/fonts/Roboto-Regular.ttf';

        if (!file_exists($fontPath)) {
    throw new \Exception("Captcha font file not found: " . $fontPath);
}

        $image->text($code, (int)($width / 4), (int)($height / 1.5), function ($font) use ($fontPath, $textColor) {
            $font->file($fontPath);
            $font->size(28);
            $font->color($textColor);
        });

        return (string) $image->toPng();
    }

    public function img(): string
    {
        $route = $this->config['route'] ?? 'captcha/image';

        return '<img src="' . url($route) . '?' . time() . '" alt="captcha">';
    }
}
