<?php
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
     */
    public function add($chunk)
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
    }

    /**
     * @param callable $callback
     * @return $this
     */
    public function subscribe(callable $callback)
    {
        $this->callbacks[] = $callback;
        return $this;
    }

    /**
     * @return string
     */
    public function flush()
    {
        $result = $this->data;
        $this->data = '';

        foreach ($this->callbacks as $callback) {
            $callback($result);
        }

        return $result;
    }

    /**
     * @return int
     */
    public function size()
    {
        return strlen($this->data);
    }
}