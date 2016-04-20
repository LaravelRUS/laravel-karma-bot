<?php
declare(strict_types = 1);
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 28.03.2016 14:06
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Domains\Message;

use Core\Mappers\Message\MessageMapper;
use Domains\User\Mention;
use Domains\User\User;

/**
 * Class Message
 * @package Domains\Message
 */
class Message extends MessageMapper
{
    /**
     * @param User $user
     * @return bool
     */
    public function isAppealTo(User $user) : bool
    {
        // return null !== (new Collection($this->mentions->toArray()))
        //     ->filter(function (Mention $mention) use ($user) {
        //         return $mention->isMentionOf($user);
        //     })
        //     ->first();
    }

    /**
     * @param User $to
     * @return $this|Mention
     */
    public function addMention(User $to) : Mention
    {
        // $mention = new Mention($to, $this);
        // $this->mentions->add($mention);
        // return $mention;
    }

    /**
     * @param string $text
     * @return $this
     */
    public function updateMessageText(string $text)
    {
        $this->text->update($text);
        $this->attributes['text'] = $text;
        $this->touch();

        return $this;
    }


    /**
     * @return bool
     */
    public function hasMentions() : bool
    {
        return $this->mentions->count() > 0;
    }
}
