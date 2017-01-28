<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Autoloaded Message Adapters
    |--------------------------------------------------------------------------
    |
    | The message component adapters listed here will be automatically
    | loaded on the start of your application. Feel free to add your
    | own message components to this array to grant realtime message
    | rendering and parsing.
    |
    */

    'adapters' => [
        'gitter' => \Serafim\MessageComponent\Adapter\GitterAdapter::class,
        'slack'  => \Serafim\MessageComponent\Adapter\SlackAdapter::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | System connections
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many systems "connections" as you wish, and you
    | may even configure multiple system connections of the same driver.
    | Defaults have been setup for each driver as an example of the required options.
    |
    | Supported Drivers: "gitter", "slack"
    |
    */

    'connections' => [
        //
        // Gitter connection example
        //
        // 'gitter' => [
        //     'driver'  => 'gitter',
        //     'adapter' => 'gitter',
        //     'token'   => env('GITTER_TOKEN'),
        // ],
        
        //
        // Slack connection example
        //
        // 'slack' => [
        //     'driver'  => 'slack',
        //     'adapter' => 'slack',
        //     'token'   => env('SLACK_TOKEN'),
        // ],
    ],
];
