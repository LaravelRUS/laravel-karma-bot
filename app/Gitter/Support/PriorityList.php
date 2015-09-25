<?php

/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 25.09.2015 14:47
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Gitter\Support;

/**
 * Class PriorityList
 * @package App\Gitter\Support
 */
class PriorityList implements \IteratorAggregate
{
    /**
     * @var []
     */
    protected $list = [];

    /**
     * @return PriorityList
     */
    public function __construct()
    {
        $this->list = [];
    }

    /**
     * @param $value
     * @param int $priority
     * @return $this
     */
    public function insert($value, $priority = 0)
    {
        if (!array_key_exists($priority, $this->list)) {
            $this->list[$priority] = [];
            krsort($this->list);
        }

        $this->list[$priority][] = $value;

        return $this;
    }

    /**
     * @return \Generator
     */
    public function getIterator()
    {
        foreach ($this->list as $index => $priority) {
            foreach ($priority as $item) {
                yield $priority => $item;
            }
        }
    }
}
