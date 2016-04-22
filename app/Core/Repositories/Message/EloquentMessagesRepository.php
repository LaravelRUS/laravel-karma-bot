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
namespace Core\Repositories\Message;

use Core\Repositories\Repository;
use Core\Repositories\Support\Eloquent;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Expr\OrderBy;
use Doctrine\ORM\QueryBuilder;
use Domains\Message\Message;
use Domains\Room\Room;

/**
 * Class EloquentMessagesRepository
 * @package Core\Repositories
 */
class EloquentMessagesRepository extends Repository implements
    MessagesRepository
{
    use Eloquent;

    /**
     * EloquentMessagesRepository constructor.
     */
    public function __construct()
    {
        parent::__construct(Message::class);
    }
}
