<?php
namespace Domains\Bot\Middlewares;

use Domains\Message;
use Domains\Middleware\MiddlewareInterface;

/**
 * Class MarkdownAdviserMiddleware
 */
class MarkdownAdviserMiddleware implements MiddlewareInterface
{
    /**
     * @param Message $message
     * @return mixed
     */
    public function handle(Message $message)
    {
        $text = $message->escaped_text;

        if (preg_match('/^(@.*?\s)?(?:оформи\sкод|код\sоформи).*?$/isu', $text)) {

            $hasMentions = count($message->mentions);
            $mention = null;

            if ($hasMentions) {
                $mention = $message->mentions[0]->isBot()
                    ? $message->user
                    : $message->mentions[0];
            }

            $answer = $mention
                ? trans('markdown.personal', [
                    'user'  => $mention->login,
                ])
                : trans('markdown.common');

            $message->italic($answer);

            return null;
        }

        return $message;
    }
}
