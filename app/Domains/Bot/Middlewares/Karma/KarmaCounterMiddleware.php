<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 15.04.2016 15:35
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Domains\Bot\Middlewares\Karma;

use Core\Repositories\KarmaRepository;
use Doctrine\ORM\EntityManager;
use Domains\Bot\Middlewares\Karma\Validation\Status;
use Domains\Bot\Middlewares\Karma\Validation\Validator;
use Domains\Bot\Middlewares\Middleware;
use Domains\Message\Message;
use Domains\User\User;

/**
 * Проверяет "спасибо" и выводит инкремент.
 *
 * Class KarmaCounterMiddleware
 */
class KarmaCounterMiddleware implements Middleware
{
    /**
     * @var Validator
     */
    protected $validator;

    /**
     * KarmaCounterMiddleware constructor.
     * @param KarmaRepository $repository
     */
    public function __construct(KarmaRepository $repository)
    {
        $this->validator = new Validator($repository);
    }

    /**
     * @param User $bot
     * @param Message $message
     * @param EntityManager $em
     * @return mixed
     */
    public function handle(User $bot, Message $message, EntityManager $em)
    {
        $collection = $this->validator->validate($message);
        $answers = [];

        /** @var Status $state */
        foreach ($collection as $state) {
            if ($state->isIncrement()) {
                $message->user->addKarma($state->user, $message);

                if ($state->user->is($bot)) {
                    $answers[] = trans('karma.bot', [
                        'user' => $message->user->credinals->login,
                    ]);
                }
            }

            if (!$state->isNothing()) {
                $count = $state->user->karma->count();
                $answers[] = $state->getTranslation($count);
            }
        }

        if ($answers !== []) {
            return $answers;
        }
    }
}
