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
use Domains\Message\Message;

/**
 * Class MessageRepository
 * @package Core\Repositories
 */
class MessageRepository extends Repository
{
    /**
     * MessageRepository constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        parent::__construct($em, $em->getClassMetadata(Message::class));
    }
}
