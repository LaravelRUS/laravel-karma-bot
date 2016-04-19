<?php

/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 19.04.2016 3:14
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Domains\Achieve;

use Doctrine\ORM\Mapping as ORM;
use Domains\User\User;
use EndyJasmi\Cuid;
use Gedmo\Mapping\Annotation as Gedmo;
use Serafim\Properties\Getters;

/**
 * Class Achieve
 * @package Domains\Karma
 * @ORM\Entity
 * @property-read string $id
 * @property-read string $name
 * @property-read User $user
 * @property-read \DateTime $created
 */
class Achieve
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
     * @var string
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="achievements", cascade={"persist"})
     * @ORM\JoinColumn(name="user_id")
     */
    protected $user;

    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created_at", type="datetime")
     */
    protected $created;

    /**
     * Achieve constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->id = Cuid::cuid();
        $this->name = basename(static::class);
        $this->user = $user;
        $this->created = new \DateTime();
    }
}