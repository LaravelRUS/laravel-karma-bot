<?php
namespace App\Gitter\Middleware;

use App\Message;
use App\Gitter\Client;
use App\Gitter\Karma\Validator;

/**
 * Проверяет слово "карма" и выводит статус
 *
 * Class KarmaRenderMiddleware
 * @package App\Gitter\Middleware
 */
class KarmaRenderMiddleware implements MiddlewareInterface
{
    /**
     * @param Message $message
     * @return mixed
     */
    public function handle(Message $message)
    {
        if (trim(mb_strtolower($message->text)) === 'карма') {
            $args = [
                'user' => $message->user->login,
                'karma' => $message->user->karma
            ];

            $karmaMessage = $args['karma']
                ? \Lang::get('karma.count.message', $args)
                : \Lang::get('karma.count.empty', $args);

            $message->italic($karmaMessage);
        }


        return $message;
    }
}
