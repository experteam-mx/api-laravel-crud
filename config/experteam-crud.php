<?php

return [

    /*
    |--------------------------------------------------------------------------
    | BASE CONFIG
    |--------------------------------------------------------------------------
    |
    | This option contains the base config of DHL services
    |
    */
    'appKey' => env('AUTH_MASTER_APP_KEY'),

    'model' => [],

    /*
    |--------------------------------------------------------------------------
    | API'S DEFINED
    |--------------------------------------------------------------------------
    |
    | This option contains DHL Api Security configuration
    |
    */
    'companies' => [
        'base_url' => env('DHL_API_COMPANIES_URL','https://companies.dhl.com'),
        'access_token' => null,

        'parameter_values' => [
            'post' => env('DHL_API_COMPANIES_URL_PARAMETERS_POST', '/parameters-values'),
            'get' => env('DHL_API_COMPANIES_URL_PARAMETERS_GET', '/parameters-values/'),
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Logger
    |--------------------------------------------------------------------------
    |
    */

    'logger' => [

        /*
         * Models to log
         */
        'models' => [
            // FQN for each model
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Stream Compute
    |--------------------------------------------------------------------------
    |
    */

    'listener' => [
        /*
         * Prefix witch
         */
        'prefix' => 'companies',

        /*
         * Models mapping
         */
        'map' => [
            // Example
            /*[
                'class' => 'CompanyCountry',
                'prefix' => 'companyCountry',
                'toRedis' => true,
                'toStreamCompute' => true,
                'dispatchMessage' => false,
                'relations' => [],
                'appends' => [],
            ],*/
        ],

    ],
];
