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

use Domains\Achieve\Achieve;
use Domains\Achieve\AchieveInterface;
use Domains\Karma;

/**
 * Class Thanks20Achieve
 */
class Thanks20Achieve extends Achieve implements AchieveInterface
{
    /**
     * @return string
     */
    public function getTitle() : string
    {
        return 'Благодарный';
    }

    /**
     * @return string
     */
    public function getDescription() : string
    {
        return 'Высказать 20 благодарностей.';
    }

    /**
     * @return string
     */
    public function getImage() : string
    {
        return '//karma.laravel.su/img/achievements/thanks-20.gif';
    }
}
