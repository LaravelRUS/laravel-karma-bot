<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 09.04.2016 4:17
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Core\Mappers\Message;

use Domains\Message\Message;
use Illuminate\Database\Eloquent\Model;

/**
 * Class UrlMapper
 * @package Core\Mappers\Message
 *
 * @property-read Message $message
 */
class UrlMapper extends Model
{
    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var string
     */
    protected $table = 'urls';

    /**
     * @var array
     */
    protected $fillable = ['url'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function message()
    {
        return $this->belongsTo(Message::class, 'message_id', 'id');
    }
}