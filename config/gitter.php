<?php

return [
    'token'       => env('GITTER_TOKEN', null),

    'rooms'       => [
        env('GITTER_DEBUG_ROOM', 'KarmaBot/KarmaTest') => ['*'],
        'LaravelRUS/chat' => ['common', 'karma', 'improvements', 'laravel'],
        'LaravelRUS/laravel.ru' => ['common', 'karma', 'improvements', 'laravel'],
        'LaravelRUS/GitterBot' => ['common', 'karma', 'improvements', 'laravel'],
        'DrupalRu/drupal.ru' => ['common', 'karma', 'improvements'],
        'dru-io/Drupal' => ['common', 'karma', 'improvements'],
        'yiisoft/yii2/offtopic-rus' => ['common', 'karma', 'improvements'],
        'yiisoft/yii2/rus' => ['common', 'karma', 'improvements'],
        'php-ua/symfony' => ['common', 'karma', 'improvements'],
        'php-ua/php' => ['common', 'karma', 'improvements'],
        'vuejs-ru/Discussion' => ['common', 'karma', 'improvements'],
    ],

    'middlewares' => [
        'common' => [
            // Google поисковик
            Domains\Bot\Middlewares\GoogleSearchMiddleware::class,
            // Советник по оформлению сообщений
            Domains\Bot\Middlewares\MarkdownAdviserMiddleware::class,
            // Ответы на персональные вопросы для бота
            Domains\Bot\Middlewares\PersonalAnswersMiddleware::class,
        ],

        'karma' => [
            // Вывод кармы по запросу
            Domains\Bot\Middlewares\KarmaRenderMiddleware::class,
            // Подсчёт "спасибок"
            Domains\Bot\Middlewares\KarmaCounterMiddleware::class,
            // Подписывается на создание ачивки и отправляет сообщеньку в чат
            // Domains\Bot\Middlewares\AchievementsMiddleware::class,
        ],

        'laravel' => [
			// Поиск по документации Laravel
			Domains\Bot\Middlewares\LaravelDocumentationSearcherMiddleware::class,

            // SQL билдер
            Domains\Bot\Middlewares\SqlBuilderMiddleware::class,
        ],

        'improvements' => [
            // Слишком длинные сообщения
            Domains\Bot\Middlewares\LongMessageMiddleware::class,

            // Анализ ссылок на изображения и видео
            // App\Middlewares\InlineDataMiddleware::class,
        ],
    ],


    // Subscribers
    'subscribers' => [
        // Подписывается на создание ачивки и отправляет сообщеньку в чат
        Domains\Bot\AchieveSubscriber::class,
    ],
];
