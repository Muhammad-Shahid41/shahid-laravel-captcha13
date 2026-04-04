<?php

namespace Shahid\Captcha;

use Illuminate\Support\ServiceProvider;

class CaptchaServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/captcha.php', 'captcha');

        $this->app->singleton('captcha13', function ($app) {
            return new CaptchaManager($app['config']->get('captcha', []));
        });
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'captcha13');

        $this->publishes([
            __DIR__ . '/../config/captcha.php' => config_path('captcha.php'),
        ], 'captcha13-config');

        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/captcha13'),
        ], 'captcha13-views');
    }
}
