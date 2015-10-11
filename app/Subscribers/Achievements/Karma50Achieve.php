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
namespace App\Subscribers\Achievements;

use App\Karma;
use App\Gitter\Achieve\AbstractAchieve;

/**
 * Class Karma50Achieve
 * @package App\Achieve
 */
class Karma50Achieve extends AbstractAchieve
{
    /**
     * @throws \LogicException
     */
    public function handle()
    {
        Karma::created(function (Karma $karma) {
            $count = $karma->target->karma;

            if ($count === 50) {
                $this
                    ->forUser($karma->target)
                    ->create(
                        'Полсотни',
                        'Наберите 50 кармы',
                        'https://raw.githubusercontent.com/SerafimArts/GitterBot/php_version/resources/achieve/karma-50.gif'
                    );
            }
        });
    }
}