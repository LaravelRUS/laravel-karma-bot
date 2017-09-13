<?php
namespace Domains\Bot\Middlewares;

use Domains\Message;
use Domains\Middleware\MiddlewareInterface;

/**
 * Class GoogleSearchMiddleware
 */
class GoogleSearchMiddleware implements MiddlewareInterface
{
    /**
     * @param Message $message
     * @return mixed
     */
    public function handle(Message $message)
    {
        $text = $message->escaped_text;

        if (preg_match('/^(@.*?\s)?(?:погугли|загугли|гугли)\s(.*?)$/isu', $text, $matches)) {
            if (!trim($matches[2])) {
                return $message;
            }

            $hasMentions = count($message->mentions);
            $mention = null;

            if ($hasMentions) {
                $mention = $message->mentions[0]->isBot()
                    ? $message->user
                    : $message->mentions[0];
            }

            $answer = trim($matches[1]) && $mention
                ? trans('google.personal', [
                    'user'  => $mention->login,
                    'query' => urlencode($matches[2]),
                ])
                : trans('google.common', [
                    'query' => urlencode($matches[2]),
                ]);

            $message->italic($answer);

            return null;
        }

        return $message;
    }
}
