<?php
namespace Domains\Bot\Middlewares;

use Domains\Middleware\Storage;
use Domains\Message;
use Interfaces\Gitter\Karma\Validator;
use Domains\Middleware\MiddlewareInterface;

/**
 * Проверяет "спасибо" и выводит инкремент.
 *
 * Class KarmaCounterMiddleware
 */
class KarmaCounterMiddleware implements MiddlewareInterface
{
    /**
     * @var Validator
     */
    protected $validator;

    /**
     * KarmaCounterMiddleware constructor.
     *
     * @param Validator $validator
     */
    public function __construct(Validator $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @param Message $message
     * @return mixed
     */
    public function handle(Message $message)
    {
        if ($message->user->isBot()) {
            return $message;
        }

        $collection = $this->validator->validate($message);
        $hasAnswers = false;

        foreach ($collection as $state) {
            $user = $state->getUser();

            if ($state->isIncrement()) {
                $message->user->addKarmaTo($user, $message);

                if ($user->isBot()) {
                    $message->answer(trans('karma.bot', [
                        'user' => $message->user->login
                    ]));
                }
            }

            if (!$state->isNothing()) {
                $hasAnswers = true;
                $message->italic($state->getTranslation($user->karma_text));
            }
        }

        if (!$hasAnswers) {
            return $message;
        }

        return Storage::SIGNAL_STOP;
    }
}
