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
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'google' => [
        // Search Console HTML-tag verification token (content="..." value only).
        'site_verification' => env('GOOGLE_SITE_VERIFICATION'),
    ],

    'beem' => [
        'send_url' => env('BEEM_SMS_SEND_URL', 'https://apisms.beem.africa/v1/send'),
        'api_key' => env('BEEM_SMS_API_KEY'),
        'secret_key' => env('BEEM_SMS_SECRET_KEY'),
        'sender_id' => env('BEEM_SMS_SENDER_ID'),
        'apple_url' => env('APP_STORE_URL', ''),
        'google_url' => env('PLAY_STORE_URL', ''),
    ],

];
