<?php
return [
    /*
     * one of $queries, then some words
     */
    'queries' => [
        'погугли',
        'загугли',
        'гугли',
        'почитай про',
        'rtfm',
    ],

    'personal' => '[user]:user[/user], [url=https://www.google.ru/webhp?#newwindow=1&hl=ru&q=:query]погуглил для тебя[/url]',
    'common' => '[url=https://www.google.ru/webhp?#newwindow=1&hl=ru&q=:query]помог погуглить[/url]',

    'results' => 'Можно поискать тут:',
];
