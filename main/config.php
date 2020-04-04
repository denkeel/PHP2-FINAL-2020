<?php

return [
    'name' => 'Мой магазин',
    'defaultController' => 'good',

    'components' => [
        'db' => [
            'class' => \App\services\DB::class,
            'config' => [
                'driver' => 'mysql',
                'host' => 'localhost',
                'dbname' => 'php1',
                'charset' => 'UTF8',
                'username' => 'root',
                'password' => '',
            ]
        ],
        'renderer' => [
            'class' => \App\services\renders\TwigRenderer::class
        ],
//        'goodRepository' => [
//            'class' => \App\repositories\GoodRepository::class
//        ],
        'request' => [
            'class' => \App\services\Request::class
        ],
        'basketService' => [
            'class' => \App\services\BasketService::class
        ]
    ]
];
