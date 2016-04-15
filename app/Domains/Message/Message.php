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

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Domains\Karma\Karma;
use Domains\Room\Room;
use Domains\User\Mention;
use Domains\User\User;
use EndyJasmi\Cuid;
use Gedmo\Mapping\Annotation as Gedmo;
use Illuminate\Support\Collection;
use Serafim\Properties\Getters;

/**
 * @ORM\Entity
 * @ORM\Table(name="messages")
 *
 * @property-read string $id
 * @property-read Room $room
 * @property-read User $user
 * @property-read Text $text
 * @property-read \DateTime $created
 * @property-read \DateTime $updated
 * @property-read ArrayCollection|User[] $mentions
 */
class Message
{
    use Getters;

    /**
     * @var Text
     * @ORM\Embedded(class=Text::class, columnPrefix=false)
     */
    protected $text;

    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created_at", type="datetime")
     */
    protected $created;

    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="updated_at", type="datetime")
     */
    protected $updated;

    /**
     * @var Room
     * @ORM\ManyToOne(targetEntity=Room::class, inversedBy="messages", cascade={"persist", "merge"})
     * @ORM\JoinColumn(name="room_id")
     */
    protected $room;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="messages", cascade={"persist", "merge"})
     * @ORM\JoinColumn(name="user_id")
     */
    protected $user;

    /**
     * @var string
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(type="string")
     */
    protected $id;

    /**
     * @var ArrayCollection|Mention[]
     * @ORM\OneToMany(targetEntity=Mention::class, mappedBy="message", cascade={"persist"})
     */
    protected $mentions;

    /**
     * @var ArrayCollection|Karma[]
     * @ORM\OneToMany(targetEntity=Karma::class, mappedBy="message", cascade={"persist"})
     */
    protected $karma;

    /**
     * Message constructor.
     * @param string $text
     * @param Room $room
     * @param User $user
     */
    public function __construct(string $text, Room $room, User $user)
    {
        $this->id = Cuid::cuid();
        $this->text = new Text($text);
        $this->created = new \DateTime();
        $this->updated = new \DateTime();
        $this->room = $room;
        $this->user = $user;

        $this->mentions = new ArrayCollection();
        $this->karma = new ArrayCollection();
    }

    /**
     * @param User $user
     * @return bool
     */
    public function isAppealTo(User $user) : bool
    {
        return null !== (new Collection($this->mentions->toArray()))
            ->filter(function (Mention $mention) use ($user) {
                return $mention->isMentionOf($user);
            })
            ->first();
    }

    /**
     * @param User $to
     * @return $this|Mention
     */
    public function addMention(User $to) : Mention
    {
        $mention = new Mention($to, $this);
        $this->mentions->add($mention);

        return $mention;
    }

    /**
     * @param string $text
     * @return $this
     */
    protected function updateMessageText(string $text)
    {
        $this->text->update($text);
        $this->touch();

        return $this;
    }

    /**
     * @return $this
     */
    public function touch()
    {
        $this->updated = new \DateTime();

        return $this;
    }
}
