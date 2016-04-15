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
namespace Domains\Bot\Middlewares\Karma\Validation;

use Domains\User\User;
use Lang;

/**
 * Class Status
 * @package Domains\Bot\Middlewares\Karma
 */
class Status
{
    const STATUS_NOTHING    = 2;
    const STATUS_INCREMENT  = 4;
    const STATUS_TIMEOUT    = 8;
    const STATUS_SELF       = 16;
    const STATUS_NO_USER    = 32;

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
     * @return bool
     */
    public function isNoUser()
    {
        return $this->status === static::STATUS_NO_USER;
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

            case static::STATUS_TIMEOUT:
                return Lang::get('karma.timeout', $args);

            case static::STATUS_SELF:
                return Lang::get('karma.self', $args);

            case static::STATUS_NO_USER:
                return Lang::get('karma.nouser', $args);
        }

        return null;
    }
}
