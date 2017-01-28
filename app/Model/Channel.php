<?php declare(strict_types = 1);
/**
 * This file is part of Laravel-Karma package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App\Model;

use Illuminate\Contracts\Container\Container;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Model\Scope\ChannelScope;
use App\Model\Transformer\ChannelTransformer;
use Serafim\KarmaCore\Io\ChannelInterface;

/**
 * Class Channel
 * @package KarmaBot\Model
 */
class Channel extends Model
{
    use ChannelScope;
    use ChannelTransformer;

    /**
     * @var string
     */
    protected $table = 'channels';

    /**
     * @var array
     */
    protected $fillable = ['system_id', 'sys_channel_id', 'name'];

    /**
     * @return BelongsTo
     */
    public function system(): BelongsTo
    {
        return $this->belongsTo(System::class);
    }
}
