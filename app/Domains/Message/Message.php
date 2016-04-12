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

use Core\Entity\Getters;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Domains\Room\Room;
use Domains\User\User;
use EndyJasmi\Cuid;
use Gedmo\Mapping\Annotation as Gedmo;
use Illuminate\Support\Collection;

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
     * @ORM\Embedded(class=Text::class)
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
     */
    protected $room;

    /**
     * @var User
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
     * @var array|ArrayCollection|User[]
     */
    protected $mentions = [];

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
    }

    /**
     * @param User $user
     * @return mixed
     */
    public function isAppealTo(User $user)
    {
        $mentions =
            (new Collection($this->mentions->toArray()))
                ->map(function (User $user) {
                    return $user->getIdentity();
                })
                ->toArray();

        return in_array($user->getIdentity(), $mentions, true);
    }

    /**
     * @param User $user
     * @return $this|Message
     */
    public function addMention(User $user) : Message
    {
        $this->mentions->add($user);

        return $this;
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
