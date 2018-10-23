<?php

namespace JoggApp\GoogleTranslate;

use Illuminate\Support\ServiceProvider;

class GoogleTranslateServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/googletranslate.php' => config_path('googletranslate.php'),
        ]);
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/googletranslate.php', 'googletranslate');

        $this->app->bind(GoogleTranslateClient::class, function () {
            return new GoogleTranslateClient(config('googletranslate'));
        });

        $this->app->bind(GoogleTranslate::class, function () {
            $client = app(GoogleTranslateClient::class);

            return new GoogleTranslate($client);
        });

        $this->app->alias(GoogleTranslate::class, 'laravel-google-translate');
    }
}
