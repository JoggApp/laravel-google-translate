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
    | Path to the json file containing the authentication credentials.
    |
    | The process to get this file is documented in a step by step detailed manner
    | over here:
    | https://github.com/JoggApp/laravel-google-translate/blob/master/google.md
    |-------------------------------------------------------------------------------
    */
    'key_file_path' => base_path('composer.json'),
];
