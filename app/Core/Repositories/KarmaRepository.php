<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 18.04.2016 18:47
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Core\Repositories;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Doctrine\ORM\Query\Expr\OrderBy;
use Doctrine\ORM\QueryBuilder;
use Domains\Karma\Karma;
use Domains\User\User;

/**
 * Class KarmaRepository
 * @package Core\Repositories
 */
class KarmaRepository extends Repository
{
    /**
     * MessageRepository constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, $em->getClassMetadata(Karma::class));
    }

    /**
     * @param User $user
     * @return Query
     */
    public function getLatestKarmaForUser(User $user)
    {
        $query = $this->createQueryBuilder('k');

        $this->matchUser($query, $user);

        return $query
            ->add('orderBy', new OrderBy('k.created', 'desc'))
            ->getQuery();
    }

    /**
     * @param QueryBuilder $qb
     * @param User $user
     */
    private function matchUser(QueryBuilder $qb, User $user)
    {
        $qb->where('k.target = :userId')
            ->setParameter('userId', $user->id);
    }
}
