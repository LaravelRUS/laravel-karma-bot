<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 11.04.2016 16:14
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Domains\Room;

use Core\Entity\Getters;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Domains\Karma\Karma;
use Domains\Message\Message;
use EndyJasmi\Cuid;

/**
 * Class Room
 * @package Domains\Room
 * @ORM\Entity
 * @ORM\Table(name="rooms")
 * @property-read string $id
 * @property-read string $url
 * @property-read string $title
 * @property-read Message[]|ArrayCollection $messages
 */
class Room
{
    use Getters;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $url;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $title;

    /**
     * @var string
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(type="string")
     */
    protected $id;

    /**
     * @var ArrayCollection|Message[]
     * @ORM\OneToMany(targetEntity=Message::class, mappedBy="room", cascade={"persist"})
     */
    protected $messages;

    /**
     * @var ArrayCollection|Karma[]
     * @ORM\OneToMany(targetEntity=Karma::class, mappedBy="room", cascade={"persist"})
     */
    protected $karma;

    /**
     * Room constructor.
     * @param string $url
     * @param string|null $title
     */
    public function __construct(string $url, string $title = null)
    {
        $this->id = Cuid::cuid();
        $this->url = $url;
        $this->title = $title ?: $this->url;
        $this->messages = new ArrayCollection();
        $this->karma = new ArrayCollection();
    }

    /**
     * @param Message $message
     * @return Room
     */
    public function addMessage(Message $message) : Room
    {
        $this->messages->add($message);
        return $this;
    }

    /**
     * @return string
     */
    protected function getTitle()
    {
        return (string)$this->title;
    }

    /**
     * @return string
     */
    protected function getUrl()
    {
        return (string)$this->url;
    }
}
