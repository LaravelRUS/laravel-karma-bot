<?php
declare(strict_types = 1);
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 28.03.2016 23:55
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace Domains\Analyser;

/**
 * Interface Analyser
 * @package Domains\Analyser
 */
interface Analyser
{
    /**
     * @return Analyser
     */
    public function clear() : Analyser;

    /**
     * @param \Closure|null $progress
     * @return Analyser
     */
    public function analyse(\Closure $progress = null) : Analyser;
}
