<?php
return [
    'debug' => true,
    'timezone' => 'UTC',
    'log' => [
        'path' => __DIR__ . '/storage/log/festiv.log',
    ],
    'error' => [
        'view' => 'errors/http',
    ],
    'database' => [
        'default' => 'default',
        'connections' => [
            'default' => [
                'driver'    => 'mysql',
                'host'      => 'localhost',
                'database'  => 'publ',
                'username'  => 'publ',
                'password'  => 'publ',
                'charset'   => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'prefix'    => 'dev_',
            ],
        ],
    ],
    'router' => [
        'cache_disabled' => false,
        'cache_file' => __DIR__ . '/storage/router',
    ],
    'session' => [
        // if you want to use file session (default)
        'type' => 'file',
        'path' => __DIR__ . '/storage/session',

        // if you want to use redis session
        //'type' => 'redis',
        //'path' => 'tcp://127.0.0.1',

        'name' => 'FestivalSessId',
        'timeout' => 1800,
    ],
    'view' => [
        'path' => __DIR__ . '/view',
        'cache' => __DIR__ . '/storage/view'
    ],
];
