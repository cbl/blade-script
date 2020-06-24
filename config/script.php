<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Minify Scripts
    |--------------------------------------------------------------------------
    |
    | This option determines wether the compiled scripts should be minified. 
    | It is recommended to minify your scripts in production. This simplifies 
    | debugging in your local environment.
    |
    | The php minifier from Matthias Mullie is used by default.
    | https://github.com/matthiasmullie/minify
    |
    */

    'minify' => !config('app.debug'),

    /*
    |--------------------------------------------------------------------------
    | Compiled Script Path
    |--------------------------------------------------------------------------
    |
    | This option determines where all the compiled styles  will be stored for 
    | your application just like for your views.
    |
    */

    'compiled' => env(
        'SCRIPT_COMPILED_PATH',
        realpath(storage_path('framework/scripts'))
    ),

];
