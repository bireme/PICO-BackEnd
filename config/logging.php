<?php

use Monolog\Handler\StreamHandler;
use Monolog\Handler\SyslogUdpHandler;

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

    'default' => env('LOG_CHANNEL', 'stack'),

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
        'stack' => [
            'driver' => 'stack',
            'channels' => ['UnknownErrors','InternalFatal','RegularErrors','UserWarnings','VisualExceptions','UserIps','UserRequests',],
            'ignore_exceptions' => false,
        ],

        'UnknownErrors' => [
            'driver' => 'daily',
            'path' => storage_path('logs/UnknownErrors/log.log'),
            'level' => 'debug',
            'days' => 14,
        ],

        'InternalFatal' => [
            'driver' => 'daily',
            'path' => storage_path('logs/InternalFatal/log.log'),
            'level' => 'debug',
            'days' => 14,
        ],

        'InternalWarning' => [
            'driver' => 'daily',
            'path' => storage_path('logs/InternalWarning/log.log'),
            'level' => 'debug',
            'days' => 14,
        ],

        'ClientErrors' => [
            'driver' => 'daily',
            'path' => storage_path('logs/ClientErrors/log.log'),
            'level' => 'debug',
            'days' => 14,
        ],

        'Warning' => [
            'driver' => 'daily',
            'path' => storage_path('logs/UserWarnings/log.log'),
            'level' => 'debug',
            'days' => 14,
        ],

        'View' => [
            'driver' => 'daily',
            'path' => storage_path('logs/VisualExceptions/log.log'),
            'level' => 'debug',
            'days' => 14,
        ],

        'UserRequests' => [
            'driver' => 'daily',
            'path' => storage_path('logs/UserRequests/log.log'),
            'level' => 'debug',
            'days' => 14,
        ],

        'UserIps' => [
            'driver' => 'daily',
            'path' => storage_path('logs/UserIps/log.log'),
            'level' => 'debug',
            'days' => 1204,
        ],


        'slack' => [
            'driver' => 'slack',
            'url' => env('LOG_SLACK_WEBHOOK_URL'),
            'username' => 'Laravel Log',
            'emoji' => ':boom:',
            'level' > 'critical',
        ],

        'papertrail' => [
            'driver' => 'monolog',
            'level' => 'debug',
            'handler' => SyslogUdpHandler::class,
            'handler_with' => [
                'host' => env('PAPERTRAIL_URL'),
                'port' => env('PAPERTRAIL_PORT'),
            ],
        ],

        'stderr' => [
            'driver' => 'monolog',
            'handler' => StreamHandler::class,
            'formatter' => env('LOG_STDERR_FORMATTER'),
            'with' => [
                'stream' => 'php://stderr',
            ],
        ],

        'syslog' => [
            'driver' => 'syslog',
            'level' => 'debug',
        ],

        'errorlog' => [
            'driver' => 'errorlog',
            'level' => 'debug',
        ],
    ],

];
