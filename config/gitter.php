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
        env('GITTER_DEBUG_ROOM', 'KarmaBot/KarmaTest') => ['*'],
        //'LaravelRUS/chat'              => ['common', 'karma', 'improvements', 'laravel'],
        //'LaravelRUS/laravel.ru'        => ['common', 'karma', 'improvements', 'laravel'],
        'LaravelRUS/GitterBot'         => ['common', 'karma', 'improvements', 'laravel'],
        //'DrupalRu/drupal.ru'           => ['common', 'karma', 'improvements'],
        //'dru-io/Drupal'                => ['common', 'karma', 'improvements'],
        //'yiisoft/yii2/offtopic-rus'    => ['common', 'karma', 'improvements'],
        //'yiisoft/yii2/rus'             => ['common', 'karma', 'improvements'],
        //'php-ua/symfony'               => ['common', 'karma', 'improvements'],
        //'php-ua/php'                   => ['common', 'karma', 'improvements'],
        //'vuejs-ru/Discussion'          => ['common', 'karma', 'improvements'],
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
            Domains\Bot\Middlewares\Karma\KarmaRenderMiddleware::class,

            // Подсчёт "спасибок"
            Domains\Bot\Middlewares\Karma\KarmaCounterMiddleware::class,

            // Подписывается на создание ачивки и отправляет сообщеньку в чат
            Domains\Bot\Middlewares\Karma\AchievementsMiddleware::class,
        ],

        'laravel' => [
            // SQL билдер
            Domains\Bot\Middlewares\Laravel\SqlBuilderMiddleware::class,
        ],

        'improvements' => [
            // Слишком длинные сообщения
            Domains\Bot\Middlewares\Improvements\LongCodeMiddleware::class,

            // Анализ ссылок на изображения и видео
            // App\Middlewares\InlineDataMiddleware::class => Storage::PRIORITY_MINIMAL,
        ]
    ],
];
