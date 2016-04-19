<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 19.04.2016 13:02
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Interfaces\Console\Commands;

use Core\Doctrine\SqlMemoryLogger;
use Core\Repositories\KarmaRepository;
use Core\Repositories\MessageRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use Domains\Achieve\Achieve;
use Domains\Achieve\AchieveInterface;
use Domains\Achieve\Repository;
use Domains\Bot\Middlewares\Karma\AchievementsMiddleware;
use Domains\Bot\Middlewares\Karma\Validation\Status;
use Domains\Bot\Middlewares\Karma\Validation\Validator;
use Domains\Message\Message;
use Domains\Room\Room;
use Domains\User\User;
use Gitter\Client;
use Gitter\Support\RequestIterator;
use Illuminate\Database\Query\Builder;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Collection;
use Interfaces\Gitter\Factories\Room as RoomFactory;
use Illuminate\Console\Command;
use Interfaces\Gitter\Io;

/**
 * Class GitterKarmaRecount
 * @package Interfaces\Console\Commands
 */
class GitterKarmaRecount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gitter:recount';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start gitter karma recounter.';

    /**
     * @param EntityManager $em
     * @param MessageRepository $messages
     * @throws \Throwable
     * @throws \Exception
     */
    public function handle(EntityManager $em, MessageRepository $messages)
    {
        $validator = new Validator(new KarmaRepository($em));

        $iterator = $this->getIterator($messages);


        $em->beginTransaction();
        try {
            \DB::table('karma')->truncate();
            \DB::table('achievements')->truncate();


            /** @var Repository $repo */
            $repo = app(Repository::class);
            $repo->subscribe(function(AchieveInterface $achieve, User $user) use ($em) {
                $user->addAchieve($achieve);
                $em->merge($user);
                $em->flush();
            });

            /** @var Message $message */
            foreach ($iterator as $message) {
                /** @var Status[]|Collection $mentions */
                $mentions = $validator->validate($message);
                foreach ($mentions as $state) {
                    if ($state->isIncrement()) {
                        $message->user->addKarma($state->user, $message);
                        $em->merge($state->user);
                    }
                }
            }

            $em->flush();
            $em->commit();
            
        } catch (\Throwable $e) {
            $em->rollback();
            $this->error($e->getMessage());
        }

    }

    /**
     * @param MessageRepository $messages
     * @return RequestIterator
     */
    protected function getIterator(MessageRepository $messages) : RequestIterator
    {
        return new RequestIterator(function($page) use ($messages) {
            $perPage = 100;

            return $messages
                ->getLatestMessages()
                ->setMaxResults($perPage)
                ->setFirstResult($page * $perPage)
                ->getResult();
        });
    }
}