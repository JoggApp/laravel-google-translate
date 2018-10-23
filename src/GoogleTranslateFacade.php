<?php

namespace JoggApp\GoogleTranslate;

use Illuminate\Support\Facades\Facade;

/**
 * @see \JoggApp\GoogleTranslate\GoogleTranslate
 */
class GoogleTranslateFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'laravel-google-translate';
    }
}
