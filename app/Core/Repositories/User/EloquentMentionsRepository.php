<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 20.04.2016 20:46
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Core\Repositories\User;

use Core\Repositories\Repository;
use Core\Repositories\Support\Eloquent;
use Domains\User\Mention;
use Illuminate\Support\Collection;

/**
 * Class EloquentMentionsRepository
 * @package Core\Repositories\User
 */
class EloquentMentionsRepository extends Repository implements
    MentionsRepository
{
    use Eloquent;

    /**
     * EloquentMentionsRepository constructor.
     */
    public function __construct()
    {
        parent::__construct(Mention::class);
    }

    /**
     * @param string $authorId
     * @return Mention[]|Collection
     */
    public function findByUserId(string $authorId) : Collection
    {
        return $this->query()
            ->where('user_id', $authorId)
            ->first();
    }

    /**
     * @param string $targetId
     * @return Mention[]|Collection
     */
    public function findByAlertId(string $targetId) : Collection
    {
        return $this->query()
            ->where('user_target_id', $targetId)
            ->first();
    }

    /**
     * @param string $messageId
     * @return Mention[]|Collection
     */
    public function findByMessageId(string $messageId) : Collection
    {
        return $this->query()
            ->where('message_id', $messageId)
            ->get();
    }

    /**
     * @param string $authorId
     * @param string $targetId
     * @param string $messageId
     * @return Mention|null
     */
    public function findByCriteria(string $authorId, string $targetId, string $messageId)
    {
        return $this->query()
            ->where('user_id', $authorId)
            ->where('user_target_id', $targetId)
            ->where('message_id', $messageId)
            ->first();
    }
}