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

use Domains\Message\Message;
use Domains\Bot\Middlewares\Middleware;
use Domains\User\Bot;
use Domains\User\User;
use Illuminate\Support\Collection;

/**
 * Class MarkdownAdviserMiddleware
 * @package Domains\Bot\Middlewares\Common
 */
class MarkdownAdviserMiddleware implements Middleware
{
    /**
     * @param Bot $bot
     * @param Message $message
     * @return Message|void
     */
    public function handle(Bot $bot, Message $message)
    {
        $isMarkdownQuery = $this->hasMarkdownQuery($message);

        if ($isMarkdownQuery) {
            if (count($message->mentions)) {
                /** @var User $mentionTo */
                $mentionTo = $message->mentions->first();

                $answerTo = $mentionTo->id === $bot->id
                    ? $message->user
                    : $mentionTo;

                return trans('markdown.personal', [
                    'user'  => $answerTo->credinals->login
                ]);
            }

            return trans('markdown.common');
        }
    }

    /**
     * @param Message $message
     * @return string
     */
    private function hasMarkdownQuery(Message $message) : string
    {
        $words   = (new Collection(trans('markdown.queries')))->map('preg_quote')->implode('|');
        $pattern = sprintf('/^(?:@.*?\s)?(?:%s).*?$/isu', $words);

        return preg_match($pattern, $message->text->inline, $matches);
    }
}
