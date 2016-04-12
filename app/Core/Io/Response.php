<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 12.04.2016 15:37
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Core\Io;

/**
 * Interface Response
 * @package Core\Io
 */
interface Response
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
     * @return $this|Response
     */
    public function disable() : Response;

    /**
     * @return $this|Response
     */
    public function enable() : Response;

    /**
     * @param mixed $data
     * @return bool
     */
    public function send($data) : bool;
}
