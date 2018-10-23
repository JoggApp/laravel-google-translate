<?php

namespace JoggApp\GoogleTranslate;

use Exception;
use Google\Cloud\Translate\TranslateClient;
use JoggApp\GoogleTranslate\Traits\SupportedLanguages;

class GoogleTranslateClient
{
    use SupportedLanguages;

    private $translate;

    public function __construct(array $config)
    {
        $this->checkForInvalidConfiguration($config);

        $this->translate = new TranslateClient([
            'keyFilePath' => $config['key_file_path']
        ]);
    }

    public function detectLanguage(string $text)
    {
        return $this->translate
            ->detectLanguage($text);
    }

    public function detectLanguageBatch(array $input)
    {
        return $this->translate
            ->detectLanguageBatch($input);
    }

    public function translate(string $text, string $translateTo)
    {
        return $this->translate
            ->translate($text, ['target' => $translateTo, 'format' => 'text']);
    }

    public function translateBatch(array $input, string $translateTo)
    {
        return $this->translate
            ->translateBatch($input, ['target' => $translateTo, 'format' => 'text']);
    }

    public function getAvaliableTranslationsFor(string $languageCode)
    {
        return $this->translate
            ->localizedLanguages(['target' => $languageCode]);
    }

    private function checkForInvalidConfiguration(array $config)
    {
        if (!file_exists($config['key_file_path'])) {
            throw new Exception('The json file does not exist at the given path');
        }

        $codeInConfig = $config['default_target_translation'];

        $languageCodeIsValid = is_string($codeInConfig)
            && ctype_lower($codeInConfig)
            && in_array($codeInConfig, $this->languages());

        if (!$languageCodeIsValid) {
            throw new Exception(
                'The default_target_translation value in the config/googletranslate.php file should
                be a valid lowercase ISO 639-1 code of the language'
            );
        }
    }
}
