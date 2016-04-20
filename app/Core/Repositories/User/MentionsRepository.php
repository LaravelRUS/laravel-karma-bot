<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 20.04.2016 20:41
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Core\Repositories\User;
use Domains\User\Mention;
use Illuminate\Support\Collection;

/**
 * Interface MentionsRepository
 * @package Core\Repositories\User
 */
interface MentionsRepository
{
    /**
     * @param string $authorId
     * @return Mention[]|Collection
     */
    public function findByUserId(string $authorId) : Collection;

    /**
     * @param string $targetId
     * @return Mention[]|Collection
     */
    public function findByAlertId(string $targetId) : Collection;

    /**
     * @param string $messageId
     * @return Mention[]|Collection
     */
    public function findByMessageId(string $messageId) : Collection;

    /**
     * @param string $authorId
     * @param string $targetId
     * @param string $messageId
     * @return Mention|null
     */
    public function findByCriteria(string $authorId, string $targetId, string $messageId);
}