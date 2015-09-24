<?php

/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 24.09.2015 00:00
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Gitter;
use Carbon\Carbon;

/**
 * Class Logger
 * @package App\Gitter
 */
class Logger
{
    /**
     * @var bool|false
     */
    protected $enabled = false;

    /**
     * @param bool|false $enabled
     */
    public function __construct($enabled = false)
    {
        $this->enabled = $enabled;
    }

    /**
     * @param $message
     * @return $this
     */
    public function write($message)
    {
        if ($this->enabled) {
            ob_start();
            var_dump($message);
            $result = ob_get_contents();
            ob_end_clean();

            $time = Carbon::now()->toTimeString();
            echo '[' . $time . '] > ' . $result . "\n";
            flush();
        }

        return $this;
    }
}
