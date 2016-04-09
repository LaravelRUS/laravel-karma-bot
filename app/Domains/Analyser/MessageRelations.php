<?php
declare(strict_types = 1);
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 28.03.2016 23:55
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Domains\Analyser;

use Core\Lazy\Fetch;
use Domains\Message\Message;
use Domains\Message\Relation;
use Domains\User\User;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class MessageRelations
 * @package Domains\Analyser
 */
class MessageRelations implements Analyser
{
    /**
     * @var Collection
     */
    private $buffer;

    /**
     * MessageRelations constructor.
     */
    public function __construct()
    {
        $this->buffer = new Collection();
    }

    /**
     * @return Analyser
     */
    public function clear() : Analyser
    {
        Relation::query()->delete();
        return $this;
    }

    /**
     * @param \Closure|null $progress
     * @return Analyser
     */
    public function analyse(\Closure $progress = null) : Analyser
    {
        $stackCount = 100;
        $response = new Fetch(
            Message::inHistoricalOrder()
                ->with('mentions', 'user')
        );

        /** @var Message $message */
        foreach ($response as $i => $message) {
            $this->buffer->push($message);
            if ($this->buffer->count() > $stackCount) {
                $this->buffer->shift();
            }

            $relations = $this->getRelations($message);

            if (count($relations)) {
                $message->questions()->saveMany($relations);
            }
            
            if ($progress !== null) {
                $progress($message, $relations, $i);
            }
        }

        return $this;
    }

    /**
     * @param Message $message
     * @return array
     */
    private function getRelations(Message $message) : array
    {
        $relations = [];

        /** @var User $mention */
        foreach ($message->mentions as $mention) {
            $question = $this->buffer->last(function ($i, Message $question) use ($mention) {
                if ($question->user) {
                    return $question->user->getId() === $mention->getId();
                }

                return false;
            });

            if ($question) {
                $relations[] = $question;
            }
        }

        return $relations;
    }

}
