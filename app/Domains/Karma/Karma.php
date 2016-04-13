<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 14.04.2016 0:29
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Domains\Karma;

use Core\Entity\Getters;
use Doctrine\ORM\Mapping as ORM;
use Domains\Message\Message;
use Domains\Room\Room;
use Domains\User\User;
use EndyJasmi\Cuid;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Class Karma
 * @package Domains\Karma
 * @ORM\Entity
 * @ORM\Table(name="karma")
 * @property-read string $id
 * @property-read Room $room
 * @property-read Message $message
 * @property-read User $user
 * @property-read User $target
 * @property-read int $value
 * @property-read \DateTime $created
 */
class Karma
{
    use Getters;

    /**
     * @var string
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(type="string")
     */
    protected $id;

    /**
     * @var Room
     * @ORM\ManyToOne(targetEntity=Room::class, inversedBy="karma", cascade={"persist"})
     * @ORM\JoinColumn(name="room_id")
     */
    protected $room;

    /**
     * @var Message
     * @ORM\ManyToOne(targetEntity=Message::class, inversedBy="karma", cascade={"persist"})
     * @ORM\JoinColumn(name="message_id")
     */
    protected $message;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="karma", cascade={"persist"})
     * @ORM\JoinColumn(name="user_id")
     */
    protected $user;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="thanks", cascade={"persist"})
     * @ORM\JoinColumn(name="user_target_id")
     */
    protected $target;

    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created_at", type="datetime")
     */
    protected $created;

    /**
     * Karma constructor.
     * @param User $from
     * @param User $to
     * @param Message $forMessage
     */
    public function __construct(User $from, User $to, Message $forMessage)
    {
        $this->id = Cuid::cuid();
        $this->room = $forMessage->room;
        $this->message = $forMessage;
        $this->user = $from;
        $this->target = $to;
        $this->created = new \DateTime();
    }
}

