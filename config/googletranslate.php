<?php

return [
    /*
      |----------------------------------------------------------------------------------------------------
      | The ISO 639-1 code of the language in lowercase to which the text will be translated to by default.
      |----------------------------------------------------------------------------------------------------
      */
    'default_target_translation' => 'en',

    /*
      |-------------------------------------------------------------------------------
      | Api Key generated within Google Cloud Dashboard.
      |
      | The process to get this file is documented in a step by step detailed manner
      | over here:
      | https://github.com/JoggApp/laravel-google-translate/blob/master/google.md
      |-------------------------------------------------------------------------------
      */
    'api_key' => env('GOOGLE_TRANSLATE_API_KEY'),
];
