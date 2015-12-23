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
 * Class Karma100Achieve
 * @package App\Achieve
 */
class Karma100Achieve extends AbstractAchieve
{
    /**
     * @var string
     */
    public $title = 'Благодетель';

    /**
     * @var string
     */
    public $description = 'Набрать 100 кармы.';

    /**
     * @var string
     */
    public $image = '//karma.laravel.su/img/achievements/karma-100.gif';

    /**
     * @throws \LogicException
     */
    public function handle()
    {
        Karma::created(function (Karma $karma) {
            $count = $karma->target->karma->count();

            if ($count === 100) {
                $this->create($karma->target, $karma->created_at);
            }
        });
    }
}