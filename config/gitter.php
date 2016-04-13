<?php
return [
    /*
    |--------------------------------------------------------------------------
    | Gitter token
    |--------------------------------------------------------------------------
    |
    | You can find this token at "https://developer.gitter.im/apps" page.
    | This token must be around 40 chars length.
    |
    | Like "7815696ecbf1c96e6894b779456d330eecbf1c" (this is example)
    |
    */
    'token' => env('GITTER_TOKEN', null),

    /*
    |--------------------------------------------------------------------------
    | Gitter room configurations
    |--------------------------------------------------------------------------
    |
    | This is configurations list for gitter rooms.
    | Configuration must be like:
    |
    | "room/name" => [ "middlewares-group-1", "middlewares-group-2" ]
    |
    */
    'rooms'       => [
        env('GITTER_DEBUG_ROOM', null) => ['*'],
        'LaravelRUS/chat'              => ['common', 'karma', 'laravel', 'improvements'],
        'LaravelRUS/laravel.ru'        => ['common', 'karma', 'laravel', 'improvements'],
        'LaravelRUS/GitterBot'         => ['common', 'karma', 'laravel', 'improvements'],
        'DrupalRu/drupal.ru'           => ['common', 'karma'],
        'dru-io/Drupal'                => ['common', 'karma'],
        'yii2/offtopic-rus'            => ['common', 'karma'],
        'yiisoft/yii2/rus'             => ['common', 'karma'],
        'php-ua/symfony'               => ['common', 'karma'],
        'php-ua/php'                   => ['common', 'karma'],
        'vuejs-ru/Discussion'          => ['common', 'karma'],
    ],


    // Middlewares
    'middlewares' => [
        'common' => [
            // Google поисковик
            Domains\Bot\Middlewares\Common\GoogleSearchMiddleware::class,

            // Советник по оформлению сообщений
            Domains\Bot\Middlewares\Common\MarkdownAdviserMiddleware::class,

            // Ответы на персональные вопросы для бота
            Domains\Bot\Middlewares\Common\PersonalAnswersMiddleware::class,
        ],

        'karma' => [
            // Вывод кармы по запросу
            //Domains\Bot\Middlewares\KarmaRenderMiddleware::class,

            // Подсчёт "спасибок"
            //Domains\Bot\Middlewares\KarmaCounterMiddleware::class,
        ],

        'laravel' => [
            // SQL билдер
            //Domains\Bot\Middlewares\SqlBuilderMiddleware::class,
        ],

        'improvements' => [
            // Слишком длинные сообщения
            //Domains\Bot\Middlewares\LongMessageMiddleware::class,

            // Анализ ссылок на изображения и видео
            // App\Middlewares\InlineDataMiddleware::class => Storage::PRIORITY_MINIMAL,
        ]
    ],


    // Subscribers
    'subscribers' => [
        // Подписывается на создание ачивки и отправляет сообщеньку в чат
        //Domains\Bot\AchieveSubscriber::class,
    ],
];
