<?php
namespace App\Middlewares;

use App\Message;
use App\Gitter\Middleware\MiddlewareInterface;

/**
 * Class InlineDataMiddleware
 * @package App\Gitter\Middleware
 */
class InlineDataMiddleware implements MiddlewareInterface
{
    /**
     * @param Message $message
     * @return mixed
     */
    public function handle(Message $message)
    {
        $isImage = preg_match(
            sprintf('/([^`]%s|[^\[]%1$s|[^\(]%1$s|^%1$s)/iu', 'http(?:s)?:\/\/.*?\.(?:jpg|png|jpeg|svg|bmp|gif)')
        , $message->text);

        $isVideo = preg_match(
            sprintf('/([^`]%s|[^\[]%1$s|[^\(]%1$s|^%1$s)/iu', 'http(?:s)?:\/\/(?:www\.)?(?:youtube\.com|youtu\.be)')
        , $message->text);

        if (($isImage || $isVideo) && $message->user->login !== \Auth::user()->login) {
            $answer = \Lang::get('gitter.inline', [
                'user' => $message->user->login
            ]);
            $message->italic($answer);
        }

        return $message;
    }
}
