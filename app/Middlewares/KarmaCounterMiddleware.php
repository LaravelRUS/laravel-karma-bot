<?php
namespace App\Middlewares;

use App\Gitter\Middleware\Storage;
use Domains\Message;
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
        $collection = $this->validator->validate($message);
        $hasAnswers = false;

        foreach ($collection as $state) {
            $user = $state->getUser();

            if ($state->isIncrement()) {
                $message->user->addKarmaTo($user, $message);

                if ($user->id === \Auth::user()->id) {
                    $message->answer(\Lang::get('karma.bot', [
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
