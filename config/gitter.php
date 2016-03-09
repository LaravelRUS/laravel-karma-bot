<?php
use App\Gitter\Middleware\Storage;

return [
    'token'       => env('GITTER_TOKEN', null),

    'output'      => true,

    'rooms'       => [
        //'debug' => env('GITTER_DEBUG_ROOM', '5617cdcad33f749381a8d5e5'), // Debug

        'laravel.chat'  => '52f9b90e5e986b0712ef6b9d',  // https://gitter.im/LaravelRUS/chat
        'laravel.site'  => '54053e51163965c9bc201c26',  // https://gitter.im/LaravelRUS/laravel.ru
        'laravel.bot'   => '560281040fc9f982beb1908a',  // https://gitter.im/LaravelRUS/GitterBot

        'drupal'        => '565c8d6716b6c7089cbcbd5d', // https://gitter.im/DrupalRu/drupal.ru
        'druio'         => '568a54e816b6c7089cc11010', // https://gitter.im/dru-io/Drupal

        'yii.offtop'    => '55dc21c10fc9f982beae822c', // https://gitter.im/yiisoft/yii2/offtopic-rus
        'yii.chat'      => '555086c915522ed4b3e03631', // https://gitter.im/yiisoft/yii2/rus

        'phpua.symfony' => '5497ef29db8155e6700e1e81', // https://gitter.im/php-ua/symfony
        'phpua.php'     => '549565c0db8155e6700e16c1', // https://gitter.im/php-ua/php

        'vuejs.chat'    => '55d5eaab0fc9f982beae0db8', // https://gitter.im/vuejs-ru/Discussion
    ],


    // Middlewares
    'middlewares' => [
        // Вывод кармы по запросу
        App\Middlewares\KarmaRenderMiddleware::class  => Storage::PRIORITY_DEFAULT,

        // SQL билдер
        App\Middlewares\SqlBuilderMiddleware::class   => Storage::PRIORITY_DEFAULT,

        // Google поисковик
        App\Middlewares\GoogleSearchMiddleware::class => Storage::PRIORITY_DEFAULT,

        // Советник по оформлению сообщений
        App\Middlewares\MarkdownAdviserMiddleware::class => Storage::PRIORITY_DEFAULT,

        // Слишком длинные сообщения
        App\Middlewares\LongMessageMiddleware::class => Storage::PRIORITY_DEFAULT,

        // Подсчёт "спасибок"
        App\Middlewares\KarmaCounterMiddleware::class => Storage::PRIORITY_MINIMAL,

        // Анализ ссылок на изображения и видео
        // App\Middlewares\InlineDataMiddleware::class => Storage::PRIORITY_MINIMAL,

        // Ответы на персональные вопросы для бота
        App\Middlewares\PersonalAnswersMiddleware::class => Storage::PRIORITY_MINIMAL,
    ],


    // Subscribers
    'subscribers' => [
        // Подписывается на создание ачивки и отправляет сообщеньку в чат
        App\Subscribers\AchieveSubscriber::class,
    ],
];
