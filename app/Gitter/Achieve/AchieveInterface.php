<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 11.10.2015 6:08
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App\Gitter\Achieve;

/**
 * Interface AchieveInterface
 * @package App\Achieve
 */
interface AchieveInterface
{
    /**
     * @return mixed
     */
    public function handle();
}