<?php
declare(strict_types = 1);
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 28.03.2016 14:35
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Domains\Message;

use Core\Mappers\Message\UrlMapper;

/**
 * Class Url
 *
 * @property string $url
 * @package Domains\Message
 * @property string $id
 * @property string $message_id
 * @method static \Illuminate\Database\Query\Builder|\Domains\Message\Url whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Domains\Message\Url whereMessageId($value)
 * @method static \Illuminate\Database\Query\Builder|\Domains\Message\Url whereUrl($value)
 * @mixin \Eloquent
 */
class Url extends UrlMapper
{
    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }
}
