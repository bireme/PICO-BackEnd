<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Log Channel
    |--------------------------------------------------------------------------
    |
    | This option defines the default log channel that gets used when writing
    | messages to the logs. The name specified in this option should match
    | one of the channels defined in the "channels" configuration array.
    |
    */

    'default' => env('LOG_CHANNEL', 'InternalErrors'),

    /*
    |--------------------------------------------------------------------------
    | Log Channels
    |--------------------------------------------------------------------------
    |
    | Here you may configure the log channels for your application. Out of
    | the box, Laravel uses the Monolog PHP logging library. This gives
    | you a variety of powerful log handlers / formatters to utilize.
    |
    | Available Drivers: "single", "daily", "slack", "syslog",
    |                    "errorlog", "monolog",
    |                    "custom", "stack"
    |
    */

    'channels' => [
        'InternalErrors' => [
            'driver' => 'daily',
            'path' => storage_path('logs/InternalErrors/InternalErrors.log'),
            'level' => 'debug',
            'days' => 60,
            'ignore_exceptions' => false,
        ],

        'Emergency' => [
            'driver' => 'daily',
            'path' => storage_path('logs/Emergency/Emergency.log'),
            'level' => 'debug',
            'days' => 60,
        ],
        'Performance' => [
            'driver' => 'daily',
            'path' => storage_path('logs/Performance/Performance.log'),
            'level' => 'info',
            'days' => 5,
        ],
        'DTO' => [
            'driver' => 'daily',
            'path' => storage_path('logs/DTO/DTO.log'),
            'level' => 'info',
            'days' => 3,
        ],
        'AppDebug' => [
            'driver' => 'daily',
            'path' => storage_path('logs/AppDebug/AppDebug.log'),
            'level' => 'debug',
            'days' => 30,
        ],

        'ClientDebug' => [
            'driver' => 'daily',
            'path' => storage_path('logs/ClientDebug/ClientDebug.log'),
            'level' => 'debug',
            'days' => 3,
        ],

        'AppInfo' => [
            'driver' => 'daily',
            'path' => storage_path('logs/AppInfo/AppInfo.log'),
            'level' => 'debug',
            'days' => 1,
        ],

        'Connections-In' => [
            'driver' => 'daily',
            'path' => storage_path('logs/Connections-In/Connections-In.log'),
            'level' => 'debug',
            'days' => 2,
        ],

        'Connections-Out' => [
            'driver' => 'daily',
            'path' => storage_path('logs/Connections-Out/Connections-Out.log'),
            'level' => 'debug',
            'days' => 2,
        ],
        'DeCS' => [
            'driver' => 'daily',
            'path' => storage_path('logs/DeCS/DeCS.log'),
            'level' => 'debug',
            'days' => 2,
        ],
        'DeCSImporter' => [
            'driver' => 'daily',
            'path' => storage_path('logs/DeCSImporter/DeCSImporter.log'),
            'level' => 'debug',
            'days' => 2,
        ],

        'Console' => [
            'driver' => 'daily',
            'path' => storage_path('logs/Console/Console.log'),
            'level' => 'debug',
            'days' => 60,
        ],

    ],

];
