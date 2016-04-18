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
use Domains\Message\Text;
use Domains\User\Bot;
use Domains\User\Mention;
use Domains\User\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

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
                /** @var Mention $mentionTo */
                $mentionTo = $message->mentions->first();

                $answerTo = $mentionTo->id === $bot->id
                    ? $message->user
                    : $mentionTo->user;

                return trans('markdown.personal', [
                    'user'  => $answerTo->credinals->login
                ]);
            }

            return trans('markdown.common');
        }
    }

    /**
     * @param Message $message
     * @return bool
     */
    private function hasMarkdownQuery(Message $message) : bool
    {
        $keywords = trans('markdown.queries');

        $text      = new Text($message->text);
        $text      = (new Text($text))->toLower();
        $text      = (new Text($text))->withoutSpecialChars;
        $sentences = (new Text($text))->sentences;

        foreach ($sentences as $sentence) {
            $count = 0;
            foreach ($keywords as $keyword) {
                if (Str::contains($sentence, $keyword)) {
                    $count++;
                }
            }

            if ($count >= 2) {
                return true;
            }
        }

        return false;
    }
}
