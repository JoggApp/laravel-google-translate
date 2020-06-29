<?php

namespace JoggApp\GoogleTranslate\Tests;

use JoggApp\GoogleTranslate\GoogleTranslate;
use JoggApp\GoogleTranslate\GoogleTranslateClient;
use Mockery;
use PHPUnit\Framework\TestCase;

class GoogleTranslateTest extends TestCase
{
    public $testString = 'A test string';
    public $testHtmlString = '<p>A test string</p>';

    private $translateClient;

    private $translate;

    public function setUp(): void
    {
        parent::setUp();

        $this->translateClient = Mockery::mock(GoogleTranslateClient::class);

        $this->translate = new GoogleTranslate($this->translateClient);
    }

    public function tearDown(): void
    {
        Mockery::close();
    }

    /** @test */
    public function it_can_detect_the_language_of_string_passed_to_it()
    {
        $this->translateClient
            ->shouldReceive('detectLanguage')->with($this->testString)
            ->once()
            ->andReturn(['languageCode' => 'en', 'confidence' => '']);

        $response = $this->translate->detectLanguage($this->testString);

        $this->assertIsArray($response);

        $this->assertArrayHasKey('text', $response);
        $this->assertArrayHasKey('language_code', $response);
        $this->assertArrayHasKey('confidence', $response);
    }

    /** @test */
    public function it_can_detect_the_language_of_an_array_of_strings_passed_to_it()
    {
        $this->translateClient
            ->shouldReceive('detectLanguageBatch')->with([$this->testString, $this->testString])
            ->once()
            ->andReturn([
                ['languageCode' => 'en', 'confidence' => '', 'input' => $this->testString],
                ['languageCode' => 'en', 'confidence' => '', 'input' => $this->testString]
            ]);

        $response = $this->translate->detectLanguage([$this->testString, $this->testString]);

        $this->assertIsArray($response);

        $this->assertArrayHasKey('text', $response[0]);
        $this->assertArrayHasKey('language_code', $response[0]);
        $this->assertArrayHasKey('confidence', $response[0]);
        $this->assertArrayHasKey('text', $response[1]);
        $this->assertArrayHasKey('language_code', $response[1]);
        $this->assertArrayHasKey('confidence', $response[1]);
    }

    /** @test */
    public function it_can_translate_the_string_passed_to_it()
    {
        $this->translateClient
            ->shouldReceive('translate')->with($this->testString, 'hi', 'text')
            ->once()
            ->andReturn(['source' => 'en', 'text' => '']);

        $response = $this->translate->translate($this->testString, 'hi');

        $this->assertIsArray($response);

        $this->assertArrayHasKey('source_text', $response);
        $this->assertArrayHasKey('source_language_code', $response);
        $this->assertArrayHasKey('translated_text', $response);
        $this->assertArrayHasKey('translated_language_code', $response);
    }

    /** @test */
    public function it_can_translate_the_html_string_passed_to_it()
    {
        $this->translateClient
            ->shouldReceive('translate')->with($this->testHtmlString, 'hi', 'html')
            ->once()
            ->andReturn(['source' => 'en', 'text' => '']);

        $response = $this->translate->translate($this->testHtmlString, 'hi', 'html');

        $this->assertIsArray($response);

        $this->assertArrayHasKey('source_text', $response);
        $this->assertArrayHasKey('source_language_code', $response);
        $this->assertArrayHasKey('translated_text', $response);
        $this->assertArrayHasKey('translated_language_code', $response);
    }

    /** @test */
    public function it_can_translate_an_array_of_strings_passed_to_it()
    {
        $this->translateClient
            ->shouldReceive('translateBatch')->with([$this->testString, $this->testString], 'hi', 'text')
            ->once()
            ->andReturn([
                ['source' => 'en', 'text' => '', 'input' => $this->testString],
                ['source' => 'en', 'text' => '', 'input' => $this->testString]
            ]);

        $response = $this->translate->translate([$this->testString, $this->testString], 'hi');

        $this->assertIsArray($response);

        $this->assertArrayHasKey('source_text', $response[0]);
        $this->assertArrayHasKey('source_language_code', $response[0]);
        $this->assertArrayHasKey('translated_text', $response[0]);
        $this->assertArrayHasKey('translated_language_code', $response[0]);
        $this->assertArrayHasKey('source_text', $response[1]);
        $this->assertArrayHasKey('source_language_code', $response[1]);
        $this->assertArrayHasKey('translated_text', $response[1]);
        $this->assertArrayHasKey('translated_language_code', $response[1]);
    }

    /** @test */
    public function test_the_just_translate_method_returns_just_the_translated_string()
    {
        $this->translateClient
            ->shouldReceive('translate')->with($this->testString, 'en')
            ->once()
            ->andReturn(['text' => 'A test string']);

        $response = $this->translate->justTranslate($this->testString, 'en');

        $this->assertEquals('A test string', $response);
    }

    /** @test */
    public function test_the_unless_language_is_method_does_not_translate_the_language_of_given_text_if_it_is_same_as_defined_in_that_method()
    {
        $this->translateClient
            ->shouldReceive('detectLanguage')->with($this->testString)
            ->once()
            ->andReturn(['languageCode' => 'en', 'confidence' => '']);

        $response = $this->translate->unlessLanguageIs('en', $this->testString, 'hi');

        $this->assertEquals($this->testString, $response);
    }

    /** @test */
    public function test_the_unless_language_is_method_translates_the_language_of_given_text_only_if_it_is_same_as_defined_in_that_method()
    {
        $this->translateClient
            ->shouldReceive('detectLanguage')->with($this->testString)
            ->once()
            ->andReturn(['languageCode' => 'en', 'confidence' => '']);

        $this->translateClient
            ->shouldReceive('translate')->with($this->testString, 'hi', 'text')
            ->once()
            ->andReturn(['source' => 'en', 'text' => '']);

        $response = $this->translate->unlessLanguageIs('hi', $this->testString, 'hi');

        $this->assertIsArray($response);

        $this->assertArrayHasKey('source_text', $response);
        $this->assertArrayHasKey('source_language_code', $response);
        $this->assertArrayHasKey('translated_text', $response);
        $this->assertArrayHasKey('translated_language_code', $response);
    }

    /** @test */
    public function it_sanitizes_the_language_codes()
    {
        $response = $this->translate->sanitizeLanguageCode('en');
        $this->assertEquals('en', $response);

        $response = $this->translate->sanitizeLanguageCode('     en');
        $this->assertEquals('en', $response);

        $response = $this->translate->sanitizeLanguageCode('EN');
        $this->assertEquals('en', $response);

        // 'zh-TW' is the only language code defined by google that includes uppercase letters
        $response = $this->translate->sanitizeLanguageCode('zh-TW');
        $this->assertEquals('zh-TW', $response);

        $this->expectExceptionMessage(
            'Invalid or unsupported ISO 639-1 language code -xx-,
            get the list of valid and supported language codes by running GoogleTranslate::languages()'
        );
        $this->translate->sanitizeLanguageCode('xx');
    }

    /** @test */
    public function it_validates_input_against_null_strings()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->translate->translate(null, 'en');
        $this->translate->justTranslate(null, 'en');
    }

    /** @test */
    public function it_validates_input_against_null_strings_in_a_batch()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->translate->translateBatch([null, null], 'en');
    }

    /** @test */
    public function it_validates_input_agaisnt_null_strings_when_detecting_a_language()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->translate->detectLanguage(null);
        $this->translate->detectLanguage([null, null]);
        $this->translate->detectLanguageBatch([null, null]);
    }
}
