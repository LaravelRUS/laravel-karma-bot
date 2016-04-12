<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 11.04.2016 17:45
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Domains\Bot\Middlewares\Common;

use Domains\Bot\Middlewares\Middleware;
use Domains\Message\Message;
use Domains\User\Bot;
use Illuminate\Support\Collection;

/**
 * Class PersonalAnswersMiddleware
 * @package Domains\Bot\Middlewares\Common
 */
class PersonalAnswersMiddleware implements Middleware
{
    /**
     * @param Bot $bot
     * @param Message $message
     * @return mixed
     */
    public function handle(Bot $bot, Message $message)
    {
        $hasHello = $this->isHello($bot, $message);

        if ($hasHello) {
            $answerId = array_rand(trans('personal.hello.answers'));

            return trans('personal.hello.answers.' . $answerId, [
                'user' => $message->user->credinals->login
            ]);
        }
    }

    /**
     * @param Bot $bot
     * @param Message $message
     * @return mixed
     */
    private function isHello(Bot $bot, Message $message)
    {
        $words = (new Collection(trans('personal.hello.queries')))
            ->map('preg_quote')
            ->implode('|');

        $pattern = $message->isAppealTo($bot)
            ? sprintf('/^\s*(?:@.*?)\s+(?:%s)$/isu', $words)
            : sprintf('/^(?:%s)$/isu', $words);

        return preg_match($pattern, trim($message->text->text));
    }
}
