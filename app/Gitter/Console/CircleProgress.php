<?php

/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 25.09.2015 16:43
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Gitter\Console;

/**
 * Class CircleProgress
 * @package App\Gitter\Console
 */
class CircleProgress
{
    /**
     * @var int
     */
    protected $iterator = 0;

    /**
     * @var array
     */
    protected static $icons = ['|', '/', '−', '\\'];

    /**
     * @return mixed
     */
    public function get()
    {
        if ($this->iterator >= count(static::$icons)) {
            $this->iterator = 0;
        }

        return static::$icons[$this->iterator++];
    }
}
