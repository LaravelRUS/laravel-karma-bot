<?php declare(strict_types = 1);
/**
 * This file is part of Laravel-Karma package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace KarmaBot\Bot\Support;

/**
 * Class MemoryProfiler
 * @package KarmaBot\Bot\Support
 */
class MemoryProfiler
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var int
     */
    private $memory;

    /**
     * @var \Closure
     */
    private $out;

    /**
     * MemoryProfiler constructor.
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
        $this->memory = memory_get_usage(true);
        $this->out = function ($message) {
        };
    }

    /**
     * @param \Closure $onMessage
     * @return $this
     */
    public function setOutput(\Closure $onMessage)
    {
        $this->out = $onMessage;

        return $this;
    }

    /**
     * @param string|null $id
     */
    public function check(string $id = null)
    {
        $current = memory_get_usage(true);

        if ($current !== $this->memory) {
            $delta = $current - $this->memory;
            ($this->out)($id . ': ' . $this->format($delta) .
                ' [current: ' . $this->format($current) . ']');
        }

        $this->memory = $current;
    }

    /**
     * @param $memory
     * @return string
     */
    private function format($memory)
    {
        return ($memory > 0 ? '+' : '-') .
            number_format($memory / 1024, 2) . 'Kb';
    }
}
