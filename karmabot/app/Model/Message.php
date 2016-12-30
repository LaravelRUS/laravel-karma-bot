<?php declare(strict_types=1);
/**
 * This file is part of Laravel-Karma package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace KarmaBot\Model;

use Illuminate\Database\Eloquent\Model;
use KarmaBot\Model\Scope\MessageScope;
use KarmaBot\Model\Transformer\MessageTransformer;

/**
 * Class Message
 * @package KarmaBot\Model
 */
class Message extends Model
{
    use MessageScope;
    use MessageTransformer;

    /**
     * @var string
     */
    protected $table = 'messages';

    /**
     * @var array
     */
    protected $fillable = ['channel_id', 'user_id', 'body', 'created_at'];
}
