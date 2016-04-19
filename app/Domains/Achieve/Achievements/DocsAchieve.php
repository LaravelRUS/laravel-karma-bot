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
use Domains\Karma;

/**
 * Class DocsAchieve
 */
class DocsAchieve extends Achieve
{
    /**
     * @return string
     */
    public function getTitle() : string
    {
        return 'Красавчик';
    }

    /**
     * @return string
     */
    public function getDescription() : string
    {
        return 'Помог переводить документацию. Настоящий мужик!';
    }

    /**
     * @return string
     */
    public function getImage() : string
    {
        return '//karma.laravel.su/img/achievements/docs.gif';
    }

    /**
     * @throws \LogicException
     */
    public function handle()
    {
        // Only manual addition
    }
}
