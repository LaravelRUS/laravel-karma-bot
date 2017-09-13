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
namespace Domains\Bot\Achievements;

use Domains\Karma;
use Interfaces\Gitter\Achieve\AbstractAchieve;

/**
 * Class Karma500Achieve
 */
class Karma500Achieve extends AbstractAchieve
{
    /**
     * @var string
     */
    public $title = 'Рэмбо';

    /**
     * @var string
     */
    public $description = 'Набрать 500 кармы.';

    /**
     * @var string
     */
    public $image = '//karma.laravel.su/img/achievements/karma-500.gif';

    /**
     * @throws \LogicException
     */
    public function handle()
    {
        Karma::created(function (Karma $karma) {
            $count = $karma->target->karma->count();

            if ($count === 500) {
                $this->create($karma->target, $karma->created_at);
            }
        });
    }
}
