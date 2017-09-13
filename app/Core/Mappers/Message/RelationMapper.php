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
namespace Core\Mappers\Message;

use Domains\Message\Message;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Relation
 * @package Domains\Message
 * 
 * @property-read Message $answer
 * @property-read Message $question
 */
class RelationMapper extends Model
{
    /**
     * @var string
     */
    protected $table = 'message_relations';
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function answer()
    {
        return $this->hasOne(Message::class, 'id', 'answer_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function question()
    {
        return $this->hasOne(Message::class, 'id', 'message_id');
    }
}
