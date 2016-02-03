<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, Mandrill, and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'mandrill' => [
        'secret' => env('MANDRILL_SECRET'),
    ],

    'ses' => [
        'key'    => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => 'us-east-1',
    ],

    'stripe' => [
        'model'  => App\User::class,
        'key'    => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],

    'facebook' => [
        'redirect'  => 'http://dev.secondlife.com/social/auth',
        'client_id' => '439018326294413',
        'client_secret' => '7dd1c7a69c9489475ed7d5ecf8c30872',
    ],

    'twitter' => [
        'redirect'  => 'http://dev.secondlife.com/social/auth',
        'client_id' => '8k8yPjqau4NuchM117434Iw0v',
        'client_secret' => '3XE91hEGkBS0fqqBLg8ytJGXkDChPLfgifYTZnazkfTcBo1Kao',
    ],

];
