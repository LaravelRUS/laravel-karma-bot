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

use Carbon\Carbon;
use Core\Entity\Getters;
use Core\Entity\Setters;
use Doctrine\ORM\Mapping as ORM;
use Domains\Room\Room;
use Domains\User\User;
use Gedmo\Mapping\Annotation as Gedmo;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity
 * @ORM\Table(name="messages")
 *
 * @property-read Text $text
 * @property-read \DateTime $created
 * @property-read \DateTime $updated
 */
class Message
{
    use Getters, Setters;

    /**
     * @var string
     * @ORM\Column(name="gitter_id", type="string")
     */
    public $gitterId;

    /**
     * @var Text
     * @ORM\Embedded(class=Text::class)
     */
    public $text;

    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="created")
     * @ORM\Column(name="created_at", type="datetime")
     */
    public $created;

    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="updated_at", type="datetime")
     */
    public $updated;

    /**
     * @var Room
     */
    public $room;

    /**
     * @var User
     */
    public $user;

    /**
     * @var string
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(type="string")
     */
    private $id;

    /**
     * Message constructor.
     * @param string $text
     * @param Room $room
     * @param User $user
     */
    public function __construct(string $text, Room $room, User $user)
    {
        $this->id = Uuid::uuid4()->toString();
        $this->text = new Text($text);
        $this->created = Carbon::now();
        $this->updated = Carbon::now();
        $this->room = $room;
        $this->user = $user;
    }

    /**
     * @param string|\DateTime $created
     * @param string|\DateTime|null $updated
     */
    public function overwriteTimestamps($created, $updated = null)
    {
        $this->created = is_string($created)
            ? new Carbon($created)
            : $created;

        if ($updated === null) {
            $updated = $this->created;
        }

        $this->updated = is_string($updated)
            ? new Carbon($updated)
            : $updated;
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
        $this->updated = Carbon::now();

        return $this;
    }
}
