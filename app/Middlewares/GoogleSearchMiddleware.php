<?php
namespace App\Middlewares;

use App\Message;
use App\Gitter\Middleware\MiddlewareInterface;

/**
 * Class GoogleSearchMiddleware
 * @package App\Gitter\Middleware
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

        if (preg_match('/^(@.*?\s)?(?:погугли|гугли)\s(.*?)$/isu', $text, $matches)) {
            if (!trim($matches[2])) {
                return $message;
            }

            $hasMentions = count($message->mentions) && $message->mentions[0]->login !== \Auth::user()->login;

            $answer = trim($matches[1]) && $hasMentions
                ? \Lang::get('google.personal', [
                    'user' => $message->mentions[0]->login,
                    'query' => urlencode($matches[2])
                ])
                : \Lang::get('google.common', [
                    'query' => urlencode($matches[2])
                ]);

            $message->answer($answer);
        }

        return $message;
    }
}
