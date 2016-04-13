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

use Core\Entity\Getters;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Domains\Karma\Karma;
use Domains\Message\Message;
use EndyJasmi\Cuid;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 * @property-read string $id
 * @property-read string $name
 * @property-read string $avatar
 * @property-read Credinals $credinals
 * @property-read \DateTime $created
 * @property-read \DateTime $updated
 * @property-read Karma[] $karma
 * @property-read Karma[] $thanks
 */
class User implements Bot
{
    use Getters;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $avatar;

    /**
     * @var Credinals
     * @ORM\Embedded(class=Credinals::class, columnPrefix=false)
     */
    protected $credinals;

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
     * @var string
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(type="string")
     */
    protected $id;

    /**
     * @var Message[]|ArrayCollection
     * @ORM\OneToMany(targetEntity=Message::class, mappedBy="user")
     */
    protected $messages;

    /**
     * @var Karma[]
     * @ORM\OneToMany(targetEntity=Karma::class, mappedBy="target")
     */
    protected $karma;

    /**
     * @var Karma[]
     * @ORM\OneToMany(targetEntity=Karma::class, mappedBy="user")
     */
    protected $thanks;

    /**
     * User constructor.
     * @param Credinals $credinals
     * @param string|null $name
     * @param string|null $avatar
     */
    public function __construct(Credinals $credinals, string $name = null, string $avatar = null)
    {
        $this->credinals = $credinals;
        $this->name = $name ?: $credinals->login;
        $this->avatar = $avatar ?: sprintf('https://github.com/identicons/%s.png', $credinals->login);

        $this->id = Cuid::cuid();
        $this->created = new \DateTime();
        $this->updated = new \DateTime();
        $this->messages = new ArrayCollection();
        $this->karma = new ArrayCollection();
        $this->thanks = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getIdentity()
    {
        return $this->id;
    }
}
