<?php
namespace App\Middlewares;

use App\Message;
use App\Gitter\Middleware\MiddlewareInterface;

/**
 * Class LongMessageMiddleware
 * @package App\Gitter\Middleware
 */
class LongMessageMiddleware implements MiddlewareInterface
{
    const MAX_CHARS = 500;
    const MAX_LINES = 15;
    const MAX_CODE_LINES = 20;

    /**
     * @param Message $message
     * @return mixed
     */
    public function handle(Message $message)
    {
        $text = $message->text;
        $lines = count(explode("\n", $text));
        $chars = mb_strlen($text);

        if (preg_match_all('/^((`{3}\s*)(\w+)?(\s*([\w\W]+?)\n*)\2)\n*(?:[^\S\w\s]|$)/m', $text, $matches)) {

            $codeLines = 0;

            foreach ($matches[5] as $code) {

                $codeLines += count(explode("\n", $code));

            }

            if ($codeLines > self::MAX_CODE_LINES) {

                $answer = \Lang::get('long.code_personal', [
                    'user'  => $message->user->login,
                ]);

                $message->italic($answer);

                return null;
            }

        } elseif ($lines > self::MAX_LINES || $chars > self::MAX_CHARS) {

            $answer = \Lang::get('long.text_personal', [
                'user'  => $message->user->login,
            ]);

            $message->italic($answer);

            return null;
        }

        return $message;
    }
}
