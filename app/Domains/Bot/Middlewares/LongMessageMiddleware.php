<?php
namespace Domains\Bot\Middlewares;

use Domains\Message;
use Domains\Middleware\MiddlewareInterface;

/**
 * Class LongMessageMiddleware
 */
class LongMessageMiddleware implements MiddlewareInterface
{
    const MAX_CHARS = 2000;
    const MAX_LINES = 30;
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

                $answer = trans('long.code_personal', [
                    'user'  => $message->user->login,
                ]);

                $message->answer($answer);

                return null;
            }

        } elseif ($lines > self::MAX_LINES || $chars > self::MAX_CHARS) {

            $answer = trans('long.text_personal', [
                'user'  => $message->user->login,
            ]);

            $message->italic($answer);

            return null;
        }

        return $message;
    }
}
