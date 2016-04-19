<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 15.04.2016 16:56
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Core\Repositories;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Expr\OrderBy;
use Doctrine\ORM\QueryBuilder;
use Domains\Message\Message;
use Domains\Room\Room;
use LaravelDoctrine\ORM\Pagination\Paginatable;

/**
 * Class MessageRepository
 * @package Core\Repositories
 */
class MessageRepository extends Repository
{
    use Paginatable;

    /**
     * MessageRepository constructor.
     * @param EntityManagerInterface|EntityManager $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, $em->getClassMetadata(Message::class));
    }

    /**
     * @return \Doctrine\ORM\Query
     */
    public function getLatestMessages()
    {
        $query = $this->createQueryBuilder('msg');

        $this->orderLatest($query);

        return $query->getQuery();
    }

    /**
     * @param Room $room
     * @return \Doctrine\ORM\Query
     */
    public function getLatestMessagesForRoom(Room $room)
    {
        $query = $this->createQueryBuilder('msg');

        $this->matchRoom($query, $room);

        $this->orderLatest($query);

        return $query->getQuery();
    }

    /**
     * @param QueryBuilder $qb
     */
    private function orderLatest(QueryBuilder $qb)
    {
        $qb->add('orderBy', new OrderBy('msg.created', 'desc'));
    }

    /**
     * @param QueryBuilder $qb
     * @param Room $room
     */
    private function matchRoom(QueryBuilder $qb, Room $room)
    {
        $qb->where('msg.room_id = :roomId')
            ->setParameter('roomId', $room->id);
    }
}
