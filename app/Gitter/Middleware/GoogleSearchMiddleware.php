<?php
namespace App\Gitter\Middleware;

use App\Message;

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

        if (preg_match('/^(@.*?\s)?погугли\s(.*?)$/isu', $text, $matches)) {
            if (!trim($matches[2])) {
                return $message;
            }

            $answer = count($message->mentions) && trim($matches[1])
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
