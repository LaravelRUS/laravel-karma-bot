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
 * Class Karma100Achieve
 */
class Karma100Achieve extends Achieve implements AchieveInterface
{
    /**
     * @return string
     */
    public function getTitle() : string
    {
        return 'Благодетель';
    }

    /**
     * @return string
     */
    public function getDescription() : string
    {
        return 'Набрать 100 кармы.';
    }

    /**
     * @return string
     */
    public function getImage() : string
    {
        return '//karma.laravel.su/img/achievements/karma-100.gif';
    }
}
