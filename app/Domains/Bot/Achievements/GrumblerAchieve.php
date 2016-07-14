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
 * Class GrumblerAchieve
 */
class GrumblerAchieve extends AbstractAchieve
{
    /**
     * @var string
     */
    public $title = 'Почётный ворчун';

    /**
     * @var string
     */
    public $description = 'Заворчит вас до смерти';

    /**
     * @var string
     */
    public $image = '//karma.laravel.su/img/achievements/grumbler.gif';

    /**
     * @throws \LogicException
     */
    public function handle()
    {
        // This special achievement
    }
}
