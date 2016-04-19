<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 12.04.2016 16:20
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Domains\User;

use Doctrine\ORM\Mapping as ORM;

/**
 * Interface Bot
 * @package Domains\User
 * @ORM\Entity
 * @ORM\Table(name="users")
 * @ORM\AttributeOverrides({})
 */
class Bot extends User
{

}
