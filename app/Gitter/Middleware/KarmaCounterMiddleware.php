<?php
namespace App\Gitter\Middleware;

use App\Message;
use App\Gitter\Client;
use App\Gitter\Karma\Validator;

/**
 * Проверяет "спасибо" и выводит инкремент.
 *
 * Class KarmaCounterMiddleware
 * @package App\Gitter\Middleware
 */
class KarmaCounterMiddleware implements MiddlewareInterface
{
    /**
     * @var Validator
     */
    protected $validator;

    /**
     * KarmaCounterMiddleware constructor.
     */
    public function __construct()
    {
        $this->validator = new Validator();
    }

    /**
     * @param Message $message
     * @return mixed
     */
    public function handle(Message $message)
    {
        $state = $this->validator->validate($message);

        if ($state->isIncrement()) {
            foreach ($message->mentions as $user) {
                $message->user->addKarmaTo($user, $message);
                $message->italic($state->getTranslation($user, $user->karma));

                if ($user->id === \Auth::user()->id) {
                    $message->answer(\Lang::get('gitter.bot'));
                }
            }
        }

        return $message;
    }
}
