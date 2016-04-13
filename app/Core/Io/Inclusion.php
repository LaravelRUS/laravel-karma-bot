<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 13.04.2016 15:29
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Core\Io;

/**
 * Interface Inclusion
 * @package Core\Io
 */
interface Inclusion
{
    /**
     * @return bool
     */
    public function isDisabled() : bool;

    /**
     * @return bool
     */
    public function isEnabled() : bool;

    /**
     * @return $this|Inclusion
     */
    public function disable() : Inclusion;

    /**
     * @return $this|Inclusion
     */
    public function enable() : Inclusion;
}
