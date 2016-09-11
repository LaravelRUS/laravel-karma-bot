<?php
declare(strict_types = 1);
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 28.03.2016 18:07
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Domains\Message;

use Core\Mappers\Message\RelationMapper;

/**
 * Class Relation
 *
 * @package Domains\Message
 * @property-read Message $answer
 * @property-read Message $question
 * @property string $message_id
 * @property string $answer_id
 * @method static \Illuminate\Database\Query\Builder|\Domains\Message\Relation whereMessageId($value)
 * @method static \Illuminate\Database\Query\Builder|\Domains\Message\Relation whereAnswerId($value)
 * @mixin \Eloquent
 */
class Relation extends RelationMapper
{
    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }
}
