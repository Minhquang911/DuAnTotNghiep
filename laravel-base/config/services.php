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

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_REDIRECT_URI'),
    ],

    'zalopay' => [
        'app_id' => env('ZALOPAY_APP_ID'),
        'key1' => env('ZALOPAY_KEY1'),
        'key2' => env('ZALOPAY_KEY2'),
        'return_url' => env('ZALOPAY_RETURN_URL'),
        'callback_url' => env('ZALOPAY_CALLBACK_URL'),
        'create_order_url' => env('ZALOPAY_CREATE_ORDER_URL', 'https://sb-openapi.zalopay.vn/v2/create'),
        'query_order_url' => env('ZALOPAY_QUERY_ORDER_URL', 'https://sb-openapi.zalopay.vn/v2/query'),
        'app_user' => env('ZALOPAY_APP_USER', 'demo'),
        'environment' => env('ZALOPAY_ENVIRONMENT', 'sandbox'),
    ],
];