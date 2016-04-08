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
namespace Core\Subscribers\Achievements;

use Domains\Karma;
use App\Gitter\Achieve\AbstractAchieve;

/**
 * Class DocsAchieve
 * @package App\Achieve
 */
class DocsAchieve extends AbstractAchieve
{
    /**
     * @var string
     */
    public $title = 'Красавчик';

    /**
     * @var string
     */
    public $description = 'Помог переводить документацию. Настоящий мужик!';

    /**
     * @var string
     */
    public $image = '//karma.laravel.su/img/achievements/docs.gif';

    /**
     * @throws \LogicException
     */
    public function handle()
    {
        // Only manual addition
    }
}