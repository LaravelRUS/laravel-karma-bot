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
use Domains\User\User;
use Illuminate\Support\Collection;

/**
 * Class GoogleSearchMiddleware
 * @package Domains\Bot\Middlewares\Common
 */
class GoogleSearchMiddleware implements Middleware
{
    /**
     * @param Bot $bot
     * @param Message $message
     * @return string|void
     */
    public function handle(Bot $bot, Message $message)
    {
        $query = $this->getGoogleQuery($message);

        if ($query) {
            if (count($message->mentions)) {
                /** @var User $mentionTo */
                $mentionTo = $message->mentions->first();

                $answerTo = $mentionTo->getIdentity() === $bot->getIdentity()
                    ? $message->user
                    : $mentionTo;

                return trans('google.personal', [
                    'user'  => $answerTo->credinals->login,
                    'query' => urlencode($query),
                ]);
            }

            return trans('google.common', ['query' => urlencode($query)]);
        }
    }

    /**
     * @param Message $message
     * @return string
     */
    private function getGoogleQuery(Message $message) : string
    {
        $words   = (new Collection(trans('google.queries')))->map('preg_quote')->implode('|');
        $pattern = sprintf('/^(?:@.*?\s)?(?:%s)\s(.*?)$/isu', $words);
        $found   = preg_match($pattern, $message->text->escaped, $matches);

        if ($found) {
            return trim($matches[1]);
        }

        return '';
    }
}
