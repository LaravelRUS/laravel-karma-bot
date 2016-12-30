<?php

// new year (temporarily)
$newYear = date('Y') === '2017' ? 'новым годом' : 'наступающим';

return [
    'hello_query' => [
        'добрый день',
        'добрый вечер',
        'доброй ночи',
        'день добрый',
        'вечер добрый',
        'здравствуйте',
        'приветствую',
        'привет',
        'даров',
        'доброго времени суток',
        'доброго утра',
        'доброго дня',
        'доброго вечера',
        'прувет',
        'бобрый',
        'бобра',
        'боброе',
        'драсте',
        'драсьте',
        
        // eng
        
        'hello',
        'hello world',
        'hello guys',
    ],
    'hello' => [

        // new year (temporarily)
       "[user]:user[/user], привет! С {$newYear} тебя! :santa:",
       "[user]:user[/user], привет =) С {$newYear} тебя! :santa:",
       "[user]:user[/user], и тебе привет ;) С {$newYear} тебя! :santa:",
       "[user]:user[/user], здравствуй. С {$newYear} тебя! :santa:",
       "[user]:user[/user], привет, как настроение? С {$newYear} тебя! :santa:",
       "[user]:user[/user], ну рассказывай. Как жизнь? :) С {$newYear} тебя! :santa:",
       "[user]:user[/user] даров! С {$newYear} тебя! :santa:",
       "[user]:user[/user] и тебе не хворать :) С {$newYear} тебя! :santa:",
       "Приветствую тебя, [user]:user[/user]! С {$newYear} тебя! :santa:",
       "О! [user]:user[/user]! Сто лет не виделись!)  С {$newYear} тебя! :santa: Как жизнь молодецкая? Рассказывай :)",
       "Не узнал тебя, [user]:user[/user]. Богатым будешь :) С {$newYear} тебя! :santa:",
       "Привет, [user]:user[/user]! Проходи, присаживайся. В ногах правды нет :) С {$newYear} тебя! :santa:",
       "Прувет, [user]:user[/user]! С {$newYear} тебя! :santa:",
       "Будь как дома, [user]:user[/user], я ни в чем не откажу. Много мануалов, коль желаешь, покажу :) С {$newYear} тебя! :santa:",
       'Hello, [user]:user[/user]! Happy New Year! :santa:',

        /*
        // rus

        '[user]:user[/user], привет =)',
        '[user]:user[/user], и тебе привет ;)',
        '[user]:user[/user], здравствуй.',
        '[user]:user[/user], привет, как настроение?',
        '[user]:user[/user], ну рассказывай. Как жизнь? :)',
        '[user]:user[/user] даров!',
        '[user]:user[/user] и тебе не хворать :)',
        'Приветствую тебя, [user]:user[/user]!',
        'О! [user]:user[/user]! Сто лет не виделись!) Как жизнь молодецкая? Рассказывай :)',
        'Не узнал тебя, [user]:user[/user]. Богатым будешь :)',
        'Привет, [user]:user[/user]! Проходи, присаживайся. В ногах правды нет :)',
        'Прувет, [user]:user[/user]!',
        'Будь как дома, [user]:user[/user], я ни в чем не откажу. Много мануалов, коль желаешь, покажу :)',
        
        // eng
        
        'Hello, [user]:user[/user]!',
        */
    ],
];
