<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 20.04.2016 19:41
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Core\Repositories\Message;

use Domains\Message\Message;

/**
 * Interface MessagesRepository
 * @package Core\Repositories\Message
 */
interface MessagesRepository
{
    /**
     * @param $id
     * @return Message|null
     */
    public function find($id);
}