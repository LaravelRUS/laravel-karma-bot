<?php
return [
    'increment'     => 'Спасибо (+1) для [user]:user[/user] принято! Текущая карма [b]:karma[/b].',
    'timeout'       => 'Карма [user]:user[/user] не потревожена. Слишком часто её пошатывали.',
    'self'          => 'Так не честно, [user]:user[/user]. Нельзя добавлять карму самому себе.',
    'bot'           => '[user]:user[/user], [i]не за что, я всегда к твоим услугам[/i] =)',
    'nouser'        => '[user]:user[/user], в этом чате принято добавлять имя пользователя, чтобы его поблагодарить.',
    'achievements'  => '
    [list]
        [*] [i]Достижения: :achievements[/i]
    [/list]',
    'account'       => '
    [list]
        [*] [i][url=http://karma.laravel.su/user/:user]Профиль :user на laravel.su[/url][/i]
    [/list]',
    'account_yii'   => '
    [list]
        [*] [i][url=http://karma.yiiframework.ru/user/:user]Профиль :user на yiiframework.ru[/url][/i]
    [/list]',

    'count'     => [
        'message' => '[user]:user[/user], [i]Ваша карма [b]:karma[/b]. Вы благодарили [b]:thanks[/b] раз.[/i]',
        'empty'   => '[user]:user[/user], [i]Вас ещё никто не благодарил.[/i]'
    ]
];
