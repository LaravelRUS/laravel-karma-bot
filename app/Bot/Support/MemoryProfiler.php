<?php declare(strict_types = 1);
/**
 * This file is part of Laravel-Karma package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App\Bot\Support;

/**
 * Class MemoryProfiler
 * @package KarmaBot\Bot\Support
 */
class MemoryProfiler
{
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
     */
    public function __construct()
    {
        $this->memory = memory_get_usage();
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
     * @return void
     */
    public function check()
    {
        $current = memory_get_usage();

        if ($current !== $this->memory) {
            $delta = $current - $this->memory;
            ($this->out)($delta / 1024, $current / 1024);
        }

        $this->memory = $current;
    }
}
