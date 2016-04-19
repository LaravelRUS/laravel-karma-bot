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
 * Class Thanks50Achieve
 */
class Thanks50Achieve extends Achieve implements AchieveInterface
{
    /**
     * @return string
     */
    public function getTitle() : string
    {
        return 'Нахлебник';
    }

    /**
     * @return string
     */
    public function getDescription() : string
    {
        return 'Сказать 50 раз "спасибо".';
    }

    /**
     * @return string
     */
    public function getImage() : string
    {
        return '//karma.laravel.su/img/achievements/thanks-50.gif';
    }
}
