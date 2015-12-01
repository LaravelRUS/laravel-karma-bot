<?php
use App\Gitter\Middleware\Storage;

return [
    'token'       => env('GITTER_TOKEN', null),

    'output'      => true,

    'rooms'       => [
        //'debug' => env('GITTER_DEBUG_ROOM', '5617cdcad33f749381a8d5e5'), // Debug
        'chat'  => '52f9b90e5e986b0712ef6b9d',  // Laravel Chat
        'site'  => '54053e51163965c9bc201c26',  // Laravel Site
        'bot'   => '560281040fc9f982beb1908a',  // Laravel Gitter Bot

        'drupal' => '565c8d6716b6c7089cbcbd5d', // Temporary support for drupal.ru community
    ],


    // Middlewares
    'middlewares' => [
        // Вывод кармы по запросу
        App\Middlewares\KarmaRenderMiddleware::class  => Storage::PRIORITY_DEFAULT,

        // SQL билдер
        App\Middlewares\SqlBuilderMiddleware::class   => Storage::PRIORITY_DEFAULT,

        // Google поисковик
        App\Middlewares\GoogleSearchMiddleware::class => Storage::PRIORITY_DEFAULT,

        // Подсчёт "спасибок"
        App\Middlewares\KarmaCounterMiddleware::class => Storage::PRIORITY_MINIMAL,

        // Анализ ссылок на изображения и видео
        App\Middlewares\InlineDataMiddleware::class => Storage::PRIORITY_MINIMAL,

        // Ответы на персональные вопросы для бота
        App\Middlewares\PersonalAnswersMiddleware::class => Storage::PRIORITY_MINIMAL,
    ],


    // Subscribers
    'subscribers' => [
        // Подписывается на создание ачивки и отправляет сообщеньку в чат
        App\Subscribers\AchieveSubscriber::class,
    ],
];
