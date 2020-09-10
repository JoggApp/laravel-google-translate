<?php

namespace JoggApp\GoogleTranslate;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class GoogleTranslateServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/googletranslate.php' => config_path('googletranslate.php'),
        ]);

        $defaultLanguage = config('googletranslate.default_target_translation');

        Blade::directive('translate', function ($expression) use ($defaultLanguage) {
            $expression = explode(',', $expression);

            $input = $expression[0];
            $languageCode = isset($expression[1]) ? $expression[1] : $defaultLanguage;

            return "<?php echo GoogleTranslate::justTranslate($input, $languageCode); ?>";
        });
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
