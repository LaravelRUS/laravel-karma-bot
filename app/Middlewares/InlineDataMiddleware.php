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
        $isImage = preg_match('/[^\`]http(?:s)?:\/\/.*?\.(?:jpg|png|jpeg|svg|bmp|gif)/isu', $message->text);
        $isVideo = preg_match('/[^`]http(?:s)?:\/\/(?:www\.)?(?:youtube\.com|youtu\.be)/isu', $message->text);

        if ($isImage || $isVideo) {
            // Move to lang files
            $answer = sprintf('@%s, просьба оборачивать в кавычки ссылки на видео и изображения.', $message->user->login);
            $message->answer($answer);
        }

        return $message;
    }
}
