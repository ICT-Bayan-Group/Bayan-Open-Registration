<?php
// config/services.php — add this to existing services.php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    */

    'mailgun' => [
        'domain'   => env('MAILGUN_DOMAIN'),
        'secret'   => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme'   => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key'    => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    // ========================================
    // Midtrans Configuration
    // ========================================
    'midtrans' => [
        'server_key'    => env('MIDTRANS_SERVER_KEY'),
        'client_key'    => env('MIDTRANS_CLIENT_KEY'),
        'is_production' => env('MIDTRANS_IS_PRODUCTION', false),
        'snap_url'      => env('MIDTRANS_IS_PRODUCTION', false)
            ? 'https://app.midtrans.com/snap/snap.js'
            : 'https://app.sandbox.midtrans.com/snap/snap.js',
    ],

    'qiscus' => [
        'app_id'     => env('QISCUS_APP_ID'),
        'secret_key' => env('QISCUS_SECRET_KEY'),
        'channel_id' => env('QISCUS_CHANNEL_ID'),
        'language'   => env('QISCUS_WHATSAPP_LANGUAGE', 'id'),
        'templates'  => [
            'payment_link'         => env('QISCUS_TEMPLATE_PAYMENT_LINK'),
            'reminder'             => env('QISCUS_TEMPLATE_REMINDER'),
            'payment_success'      => env('QISCUS_TEMPLATE_PAYMENT_SUCCESS'),
            'rejected'             => env('QISCUS_TEMPLATE_PAYMENT_REJECTED'),
            'admin_notification'   => env('QISCUS_TEMPLATE_ADMIN_NOTIFICATION'),
        ],
    ],
];