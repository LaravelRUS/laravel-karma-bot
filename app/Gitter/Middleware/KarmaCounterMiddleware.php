<?php
namespace App\Gitter\Middleware;

use App\Message;
use App\Gitter\Client;
use App\Gitter\Karma\Validator;

/**
 * @TODO Refactor me
 *
 * Class KarmaCounterMiddleware
 * @package App\Gitter\Middleware
 */
class KarmaCounterMiddleware implements MiddlewareInterface
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var Validator
     */
    protected $validator;

    /**
     * KarmaCounterMiddleware constructor.
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;

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
            }
        }

        if ($state->isDecrement()) {
            foreach ($message->mentions as $user) {
                $message->user->robKarmaTo($user, $message);
                $message->italic($state->getTranslation($user, $user->karma));
            }
        }


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
