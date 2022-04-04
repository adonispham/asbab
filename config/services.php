<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],
    'google' => [
        'client_id'     => env('GOOGLE_CLIENT_ID', '904541494141-9mvsc48fbbmbtfhmm7evcbkt49oib51r.apps.googleusercontent.com'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET', 'fuXwEL5PV3icH9ben4AokrBZ'),
        'redirect'      => env('GOOGLE_REDIRECT', 'https://asbab.dev.com/callback/google'),
    ],
    'facebook' => [
        'client_id' => env('FACEBOOK_APP_ID', '747661122679722'),
        'client_secret' => env('FACEBOOK_APP_SECRET', 'a337bb4d43f54da98cd6ded437294a36'),
        'redirect' => env('FACEBOOK_APP_CALLBACK_URL', 'https://asbab.dev.com/callback/facebook'),
    ],
];
