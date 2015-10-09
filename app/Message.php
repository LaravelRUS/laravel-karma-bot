<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 09.10.2015 16:58
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App;

use Carbon\Carbon;
use LogicException;
use App\Mappers\MessageMapperTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Message
 * @package App
 *
 * @property string $gitter_id
 * @property string $text
 * @property string $html
 * @property bool $edited
 * @property User $user
 * @property string $unread
 * @property int $read_by
 * @property array $urls
 * @property array $mentions
 * @property array $issues
 * @property array $meta
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 */
class Message extends Model
{
    /**
     * Gitter mapper
     */
    use MessageMapperTrait;

    /**
     * @param array $options
     * @throws LogicException
     * @return void
     */
    final public function save(array $options = [])
    {
        throw new LogicException('Can not save message');
    }
}
