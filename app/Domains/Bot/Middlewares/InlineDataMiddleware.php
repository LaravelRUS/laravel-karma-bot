<?php
namespace Domains\Bot\Middlewares;

use Domains\Message\Message;


/**
 * Class InlineDataMiddleware
 */
class InlineDataMiddleware implements Middleware
{
    /**
     * @param Message $message
     * @return mixed
     */
    public function handle(Message $message)
    {
        $isImage = preg_match(
            '/[^`]http(?:s)?:\/\/.*?\.(?:jpg|png|jpeg|svg|bmp)/iu'
        , ' ' . $message->text);

        $isVideo = preg_match(
            '/[^`]http(?:s)?:\/\/(?:www\.)?(?:youtube\.com|youtu\.be).*?/iu'
        , ' ' . $message->text);

        if (($isImage || $isVideo) && $message->user->login !== \Auth::user()->login) {
            $answer = \Lang::get('gitter.inline', [
                'user' => $message->user->login
            ]);
            $message->italic($answer);
        }

        return $message;
    }
}
