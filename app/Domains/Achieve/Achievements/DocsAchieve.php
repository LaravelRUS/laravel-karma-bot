<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 11.10.2015 6:07
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Domains\Achieve\Achievements;

use Doctrine\ORM\Mapping as ORM;
use Domains\Achieve\Achieve;
use Domains\Achieve\AchieveInterface;
use Domains\Karma;

/**
 * Class DocsAchieve
 * @package Domains\Achieve\Achievements
 * @ORM\Entity
 * @ORM\Table(name="achievements")
 * @ORM\AttributeOverrides({})
 */
class DocsAchieve extends Achieve implements AchieveInterface
{
    /**
     * @return int
     */
    public function getType() : int
    {
        return static::SPECIAL;
    }

    /**
     * @return string
     */
    public function getTitle() : string
    {
        return 'Красавчик';
    }

    /**
     * @return string
     */
    public function getDescription() : string
    {
        return 'Помог переводить документацию. Настоящий мужик!';
    }

    /**
     * @return string
     */
    public function getImage() : string
    {
        return '//karma.laravel.su/img/achievements/docs.gif';
    }
}
