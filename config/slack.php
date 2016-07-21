<?php

use Domains\Middleware\Storage;

return [
    'token' => env('SLACK_TOKEN', null),
    'rooms'       => [
        'debug'         => 'C125407FG', // Debug
    ],

    'middlewares' => [
        // Вывод кармы по запросу
        Domains\Bot\Middlewares\KarmaRenderMiddleware::class  => Storage::PRIORITY_DEFAULT,

        // Google поисковик
        Domains\Bot\Middlewares\GoogleSearchMiddleware::class => Storage::PRIORITY_DEFAULT,

        // Советник по оформлению сообщений
        Domains\Bot\Middlewares\MarkdownAdviserMiddleware::class => Storage::PRIORITY_DEFAULT,

        // Слишком длинные сообщения
        Domains\Bot\Middlewares\LongMessageMiddleware::class => Storage::PRIORITY_DEFAULT,

        // Подсчёт "спасибок"
        Domains\Bot\Middlewares\KarmaCounterMiddleware::class => Storage::PRIORITY_MINIMAL,

        // Анализ ссылок на изображения и видео
        // App\Middlewares\InlineDataMiddleware::class => Storage::PRIORITY_MINIMAL,

        // Ответы на персональные вопросы для бота
        Domains\Bot\Middlewares\PersonalAnswersMiddleware::class => Storage::PRIORITY_MINIMAL,
    ],
];