<?php

namespace JoggApp\GoogleTranslate;

use Exception;
use InvalidArgumentException;
use JoggApp\GoogleTranslate\Traits\SupportedLanguages;

class GoogleTranslate
{
    use SupportedLanguages;

    private $translateClient;

    private $givenLanguageCode;

    public function __construct(GoogleTranslateClient $client)
    {
        $this->translateClient = $client;
    }

    public function detectLanguage($input): array
    {
        if (is_array($input)) {
            return $this->detectLanguageBatch($input);
        }

        $this->validateInput($input);

        $response = $this
            ->translateClient
            ->detectLanguage($input);

        return [
            'text' => $input,
            'language_code' => $response['languageCode'],
            'confidence' => $response['confidence']
        ];
    }

    public function detectLanguageBatch(array $input): array
    {
        $this->validateInput($input);

        $responses = $this
            ->translateClient
            ->detectLanguageBatch($input);

        foreach ($responses as $response) {
            $translations[] = [
                'text' => $response['input'],
                'language_code' => $response['languageCode'],
                'confidence' => $response['confidence']
            ];
        }

        return $translations;
    }

    public function translate($input, $to = null, $format = 'text'): array
    {
        $this->validateInput($input);

        $translateTo = $to ?? config('googletranslate.default_target_translation');

        $translateTo = $this->sanitizeLanguageCode($translateTo);

        if (is_array($input)) {
            return $this->translateBatch($input, $translateTo, $format);
        }

        $response = $this
            ->translateClient
            ->translate($input, $translateTo, $format);

        return [
            'source_text' => $input,
            'source_language_code' => $response['source'],
            'translated_text' => $response['text'],
            'translated_language_code' => $translateTo
        ];
    }

    public function justTranslate(string $input, $to = null): string
    {
        $this->validateInput($input);

        $translateTo = $to ?? config('googletranslate.default_target_translation');

        $translateTo = $this->sanitizeLanguageCode($translateTo);

        $response = $this
            ->translateClient
            ->translate($input, $translateTo);

        return $response['text'];
    }

    public function translateBatch(array $input, string $translateTo, $format = 'text'): array
    {
        $translateTo = $this->sanitizeLanguageCode($translateTo);

        $this->validateInput($input);

        $responses = $this
            ->translateClient
            ->translateBatch($input, $translateTo, $format);

        foreach ($responses as $response) {
            $translations[] = [
                'source_text' => $response['input'],
                'source_language_code' => $response['source'],
                'translated_text' => $response['text'],
                'translated_language_code' => $translateTo
            ];
        }

        return $translations;
    }

    public function getAvaliableTranslationsFor(string $languageCode): array
    {
        $languageCode = $this->sanitizeLanguageCode($languageCode);

        return $this->translateClient
            ->getAvaliableTranslationsFor($languageCode);
    }

    public function unlessLanguageIs(string $languageCode, string $input, $to = null)
    {
        $translateTo = $to ?? config('googletranslate.default_target_translation');

        $translateTo = $this->sanitizeLanguageCode($translateTo);

        $languageCode = $this->sanitizeLanguageCode($languageCode);

        $languageMisMatch = $languageCode != $this->detectLanguage($input)['language_code'];

        if ($languageMisMatch) {
            return $this->translate($input, $translateTo);
        }

        return $input;
    }

    public function sanitizeLanguageCode(string $languageCode)
    {
        $languageCode = trim(strtolower($languageCode));

        if ($languageCode === 'zh-tw') {
            $languageCode = 'zh-TW';
        }

        if (in_array($languageCode, $this->languages())) {
            return $languageCode;
        }

        throw new Exception(
            "Invalid or unsupported ISO 639-1 language code -{$languageCode}-,
            get the list of valid and supported language codes by running GoogleTranslate::languages()"
        );
    }

    protected function validateInput($input): void
    {
        if(is_array($input) && in_array(null, $input)) {
            throw new InvalidArgumentException('Input string cannot be null');
        }

        if(is_null($input)) {
            throw new InvalidArgumentException('Input string cannot be null');
        }

        return;
    }
}
