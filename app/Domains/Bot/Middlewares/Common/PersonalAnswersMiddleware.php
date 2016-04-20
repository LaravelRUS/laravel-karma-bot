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
use Domains\User\User;
use Illuminate\Support\Collection;

/**
 * Class PersonalAnswersMiddleware
 * @package Domains\Bot\Middlewares\Common
 */
class PersonalAnswersMiddleware implements Middleware
{
    /**
     * @param User $bot
     * @param Message $message
     * @return mixed
     */
    public function handle(User $bot, Message $message)
    {
        $hasHello = $this->isHello($bot, $message);

        if ($hasHello) {
            $answerId = array_rand(trans('personal.hello.answers'));

            return trans('personal.hello.answers.' . $answerId, [
                'user' => $message->user->credinals->login,
            ]);
        }
    }

    /**
     * @param User $bot
     * @param Message $message
     * @return mixed
     */
    private function isHello(User $bot, Message $message)
    {
        $words = (new Collection(trans('personal.hello.queries')))
            ->map('preg_quote')
            ->implode('|');

        $pattern = $message->isAppealTo($bot)
            ? sprintf('/^.*?(?:@.*?)\s+(?:%s)\W*$/isu', $words)
            : sprintf('/^\W*(?:%s)\W*$/isu', $words);

        return preg_match($pattern, trim($message->text->toString()));
    }
}
