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

use Carbon\Carbon;
use Core\Entity\Getters;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 */
class User
{
    use Getters;

    /**
     * @var string
     * @ORM\Column(name="gitter_id", type="string")
     */
    public $gitterId;

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
     * @ORM\Embedded(class=Credinals::class)
     */
    protected $credinals;

    /**
     * @var string
     * @ORM\Column(name="id", type="string")
     */
    protected $password;

    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="created")
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
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(name="id", type="string")
     */
    private $id;

    /**
     * User constructor.
     * @param Credinals $credinals
     * @param string|null $name
     * @param string|null $avatar
     */
    public function __construct(Credinals $credinals, string $name = null, string $avatar = null)
    {
        $this->id           = Uuid::uuid4()->toString();
        $this->created      = Carbon::now();
        $this->updated      = Carbon::now();

        $this->credinals    = $credinals;
        $this->name         = $name ?: $credinals->login;
        $this->avatar       = $avatar ?: sprintf('https://github.com/identicons/%s.png', $credinals->login);
    }

    /**
     * @return \DateTime
     */
    protected function getCreated()
    {
        return $this->created;
    }

    /**
     * @return \DateTime
     */
    protected function getUpdated()
    {
        return $this->created;
    }
}
