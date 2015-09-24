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

namespace App\Gitter\Support;

/**
 * Class StreamBuffer
 * @package App\Gitter\Support
 */
class StreamBuffer
{
    /**
     * @var string
     */
    protected $data = '';

    /**
     * @var array
     */
    protected $callbacks = [];

    /**
     * @param $chunk
     * @return StreamBuffer
     */
    public function add($chunk): StreamBuffer
    {
        $chunks = explode("\n", $chunk);
        $count  = count($chunks);

        if ($count === 1) {
            $this->data .= $chunk;

        } else {
            for ($i = 0; $i < $count; $i++) {
                $this->data .= $chunks[$i];
                if ($i !== $count - 1) {
                    $this->flush();
                }
            }
        }

        return $this;
    }

    /**
     * @param callable $callback
     * @return StreamBuffer
     */
    public function subscribe(callable $callback): StreamBuffer
    {
        $this->callbacks[] = $callback;
        return $this;
    }

    /**
     * @return mixed|string
     */
    public function flush(): string
    {
        $result = $this->data;
        $this->data = '';

        foreach ($this->callbacks as $callback) {
            $callback($result);
        }

        return $result;
    }

    /**
     * @return mixed|integer
     */
    public function size(): integer
    {
        return strlen($this->data);
    }
}
