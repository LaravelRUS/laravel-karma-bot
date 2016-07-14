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
 * Class Karma1000Achieve
 */
class Karma1000Achieve extends AbstractAchieve
{
    /**
     * @var string
     */
    public $title = 'Jhaoda';

    /**
     * @var string
     */
    public $description = 'Больше кармы богу кармы!';

    /**
     * @var string
     */
    public $image = '//karma.laravel.su/img/achievements/karma-1000.gif';

    /**
     * @throws \LogicException
     */
    public function handle()
    {
        Karma::created(function (Karma $karma) {
            $count = $karma->target->karma->count();

            if ($count === 1000) {
                $this->create($karma->target, $karma->created_at);
            }
        });
    }
}
