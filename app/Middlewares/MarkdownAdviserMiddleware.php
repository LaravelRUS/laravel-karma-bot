<?php
namespace App\Middlewares;

use Domains\Message;
use App\Gitter\Middleware\MiddlewareInterface;

/**
 * Class MarkdownAdviserMiddleware
 * @package App\Gitter\Middleware
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
                $mention = $message->mentions[0]->login === \Auth::user()->login
                    ? $message->user
                    : $message->mentions[0];
            }

            $answer = $mention
                ? \Lang::get('markdown.personal', [
                    'user'  => $mention->login,
                ])
                : \Lang::get('markdown.common');

            $message->italic($answer);

            return null;
        }

        return $message;
    }
}
