<?php

return [

    'default' => env('BROADCAST_DRIVER', 'pusher'),

    'connections' => [

        'pusher' => [
            'driver' => 'pusher',
            'key' => env('1979934'),
            'secret' => env('52a6039a3ac97637fd96'),
            'app_id' => env('9a2da083ab6f9874cf16'),
            'options' => [
                'cluster' => 'ap1',
                'useTLS' => true
            ],
        ],

        'redis' => [
            'driver' => 'redis',
            'connection' => 'default',
        ],

        'log' => [
            'driver' => 'log',
        ],

        'null' => [
            'driver' => 'null',
        ],

    ],

];
