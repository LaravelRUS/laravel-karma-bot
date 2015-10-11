<?php
namespace App\Middlewares;

use App\Message;
use App\Gitter\Karma\Validator;
use App\Gitter\Middleware\MiddlewareInterface;

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
                $message->italic($state->getTranslation($user, $user->karma_text));

                if ($user->id === \Auth::user()->id) {
                    $message->answer(\Lang::get('karma.bot'));
                }
            }
        }

        return $message;
    }
}
