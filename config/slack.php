<?php

return [
    'token' => env('SLACK_TOKEN', null),

    'rooms' => [
        env('SLACK_DEBUG_ROOM', null) => ['common', 'improvements']
    ],

    'middlewares' => [
        'common' => [
            // Google поисковик
            Domains\Bot\Middlewares\NewGoogleSearchMiddleware::class,
            // Domains\Bot\Middlewares\GoogleSearchMiddleware::class,

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
        ],

        'improvements' => [
            // Слишком длинные сообщения
            Domains\Bot\Middlewares\LongMessageMiddleware::class,

            // Анализ ссылок на изображения и видео
            // App\Middlewares\InlineDataMiddleware::class,
        ],
    ],
];