<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @author Big-Shark
 * @author symbios-zi
 * @author atehnix
 *
 * @date 11.04.2016 17:45
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

return [
    /*
     * one of $queries, then some words
     */
    'queries' => [
        'погугли',
        'загугли',
        'гугли',
        'почитай про',
        'rtfm'
    ],

    /*
     * If message like "@Nickname [$queries] some words there"
     */
    'personal' => '@:user, [погуглил для тебя](https://www.google.ru/webhp?#newwindow=1&hl=ru&q=:query)',

    /*
     * If message like "[$queries] some words there"
     */
    'common'   => '[помог погуглить](https://www.google.ru/webhp?#newwindow=1&hl=ru&q=:query)',
];
