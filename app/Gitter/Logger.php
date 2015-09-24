<?php
namespace App\Gitter;

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

            echo $result;
            flush();
        }

        return $this;
    }

    /**
     * @param $message
     * @return $this
     */
    public function writeln($message)
    {
        if ($this->enabled) {
            $this->write($message);
            echo "\n";
        }
        return $this;
    }
}