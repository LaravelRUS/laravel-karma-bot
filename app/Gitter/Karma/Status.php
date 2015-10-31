<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 09.10.2015 19:49
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App\Gitter\Karma;

use App\User;
use Lang;

class Status
{
    const STATUS_NOTHING = 2;
    const STATUS_INCREMENT = 4;
    const STATUS_DECREMENT = 8;
    const STATUS_TIMEOUT = 16;
    const STATUS_SELF = 32;

    /**
     * @var int
     */
    protected $status = self::STATUS_NOTHING;

    /**
     * @var User
     */
    protected $user;

    /**
     * Status constructor.
     * @param User $mention
     * @param int $status
     */
    public function __construct(User $mention, $status = self::STATUS_NOTHING)
    {
        $this->user = $mention;
        $this->status = $status;
    }

    /**
     * @return bool
     */
    public function isNothing()
    {
        return $this->status === static::STATUS_NOTHING;
    }

    /**
     * @return bool
     */
    public function isIncrement()
    {
        return $this->status === static::STATUS_INCREMENT;
    }

    /**
     * @return bool
     */
    public function isDecrement()
    {
        return $this->status === static::STATUS_DECREMENT;
    }

    /**
     * @return bool
     */
    public function isTimeout()
    {
        return $this->status === static::STATUS_TIMEOUT;
    }

    /**
     * @return bool
     */
    public function isSelf()
    {
        return $this->status === static::STATUS_SELF;
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param $karma
     * @return string|null
     */
    public function getTranslation($karma)
    {
        $args = ['user' => $this->user->login, 'karma' => $karma];

        switch ($this->status) {
            case static::STATUS_INCREMENT:
                return Lang::get('karma.increment', $args);

            case status::STATUS_DECREMENT:
                return Lang::get('karma.decrement', $args);

            case status::STATUS_TIMEOUT:
                return Lang::get('karma.timeout', $args);

            case status::STATUS_SELF:
                return Lang::get('karma.self', $args);
        }

        return null;
    }
}
