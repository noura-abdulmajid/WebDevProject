<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    |
    | This option defines the default authentication "guard" and password
    | reset "broker" for your application. You may change these values as
    | required, but they provide a great starting point for most projects.
    |
    */

    'defaults' => [
        'guard' => env('AUTH_GUARD', 'web'),
        'passwords' => env('AUTH_PASSWORD_BROKER', 'customers'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    |
    | Here you may define every authentication guard for your application.
    | A great default configuration has been defined for you which uses
    | session storage and the Eloquent user provider. You may add more
    | guards as needed for different parts of the application.
    |
    | Supported drivers: "session", "token", "jwt"
    |
    */

    'guards' => [

        'web' => [
            'driver' => 'session',
            'provider' => 'customers',
        ],

        'api' => [
            'driver' => 'jwt',
            'provider' => 'customers',
        ],

        'admin_api' => [
            'driver' => 'jwt',
            'provider' => 'admin_users',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    |
    | All authentication guards use a user provider to retrieve user data
    | from your database or any other storage implementations. You may
    | configure multiple providers for different user models or tables.
    |
    | Supported: "database", "eloquent"
    |
    */

    'providers' => [
        'customers' => [
            'driver' => 'eloquent',
            'model' => App\Models\Customer::class,
        ],


        // User provider for admin users
        'admin_users' => [
            'driver' => 'eloquent',
            'model' => App\Models\AdminUsers::class,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Resetting Passwords
    |--------------------------------------------------------------------------
    |
    | You can configure password reset settings here, which includes the
    | table to store reset tokens and the expiration time for the token.
    | Make sure to set these configurations according to your use case.
    |
    */

    'passwords' => [
        'customers' => [
            'provider' => 'customers',
            'table' => env('AUTH_PASSWORD_RESET_TOKEN_TABLE', 'password_reset_tokens'),
            'expire' => 60, // Token expiration time in minutes
            'throttle' => 60, // Throttling interval in seconds for reset requests
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Confirmation Timeout
    |--------------------------------------------------------------------------
    |
    | Here you may define the number of seconds before password confirmation
    | expires and requires the user to re-enter their password. The default
    | timeout lasts for three hours.
    |
    */

    'password_timeout' => env('AUTH_PASSWORD_TIMEOUT', 10800),

];
