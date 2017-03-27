<?php
/**
 * This file is part of GitterBot package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Domains\Bot\Achievements;

use Domains\Karma;
use Interfaces\Gitter\Achieve\AbstractAchieve;

/**
 * Class ReaderAchieve
 */
class ReaderAchieve extends AbstractAchieve
{
    /**
     * @var string
     */
    public $title = 'Чтец';

    /**
     * @var string
     */
    public $description = 'Внемлил гласу разума и прочитал доки';

    /**
     * @var string
     */
    public $image = '//karma.laravel.su/img/achievements/reader.gif';

    /**
     * @throws \LogicException
     */
    public function handle()
    {
        // Only manual addition
    }
}
