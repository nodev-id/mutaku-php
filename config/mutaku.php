<?php
return [
    /*
    |--------------------------------------------------------------------------
    | Orderkuota Auth Token ID
    |--------------------------------------------------------------------------
    |
    | Auth Token dari aplikasi Orderkuota.
    |
    */
    'authToken' => env('ORDERKUOTA_AUTH_TOKEN', ''),

    /*
    |--------------------------------------------------------------------------
    | Orderkuota Account Username
    |--------------------------------------------------------------------------
    |
    | username akun Orderkuota.
    |
    */
    'accountUsername' => env('ORDERKUOTA_ACCOUNT_USERNAME', ''),

    /*
    |--------------------------------------------------------------------------
    | Orderkuota server url
    |--------------------------------------------------------------------------
    |
    | server Orderkuota.
    | default: https://app.orderkuota.com/api
    |
    */
    'serverUrl' => env('ORDERKUOTA_SERVER_URL', 'https://app.orderkuota.com/api'),
];
