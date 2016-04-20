<?php
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

use Core\Mappers\User\UserMapper;
use Domains\Achieve\AchieveInterface;
use Domains\Karma\Karma;
use Domains\Message\Message;

/**
 * Class User
 * @package Domains\User
 */
class User extends UserMapper
{
    /**
     * @param User $target
     * @param Message $forMessage
     * @return Karma
     */
    public function thank(User $target, Message $forMessage) : Karma
    {
        // $karma = new Karma($this, $target, $forMessage);
        // $this->karma->add($karma);
        // return $karma;
    }

    /**
     * @param Message $message
     * @return $this|Message
     */
    public function write(Message $message) : Message
    {
        // $this->messages->add($message);
        // return $this;
    }

    /**
     * @param AchieveInterface $achieve
     * @return User
     */
    public function addAchieve(AchieveInterface $achieve) : User
    {
        // $this->achievements->add($achieve);
        // return $this;
    }

    /**
     * @param User $to
     * @param Message $fromMessage
     * @return Karma
     */
    public function addKarma(User $to, Message $fromMessage) : Karma
    {
        // $karma = new Karma($this, $to, $fromMessage);
        // $to->karma->add($karma);
        // return $karma;
    }

    /**
     * @param User $user
     * @return bool
     */
    public function is(User $user) : bool
    {
        return $this->id === $user->id;
    }

    /**
     * @return string
     */
    public function __toString() : string
    {
        return (string)$this->login;
    }
}
