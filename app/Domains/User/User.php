<?php
declare(strict_types = 1);
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 23.03.2016 20:17
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Domains\User;

use Carbon\Carbon;
use Domains\Message\Message;
use Illuminate\Support\Collection;

/**
 * Class User
 * TODO This class doesnot implemented Yet
 *
 * @package Domains\User
 * @property string $login
 * @property string $name
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Mention[]|Collection $mentions
 * @property-read Message[]|Collection $messages
 */
class User extends \Eloquent
{
    /**
     * @var string
     */
    protected $table = 'users';

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function mentions()
    {
        return $this->hasMany(Mention::class, 'user_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function messages()
    {
        return $this->hasMany(Message::class, 'user_id', 'id');
    }
}
