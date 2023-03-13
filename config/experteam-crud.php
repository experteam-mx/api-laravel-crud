<?php

return [
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
                'dispatchMessage' => false
            ],*/
        ],

    ],
];
