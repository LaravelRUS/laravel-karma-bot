<?php
declare(strict_types = 1);
/**
 * This file is part of Ai package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 28.03.2016 14:02
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace Core\Lazy;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class Fetch
 * @package Core\Lazy
 */
class Fetch implements \IteratorAggregate
{
    const DEFAULT_CHUNK_SIZE = 1000;

    /**
     * @var int
     */
    private $chunk = self::DEFAULT_CHUNK_SIZE;

    /**
     * @var Builder
     */
    private $builder;

    /**
     * Fetch constructor.
     * @param Builder $builder
     * @param int $chunk
     */
    public function __construct(Builder $builder, $chunk = self::DEFAULT_CHUNK_SIZE)
    {
        $this->builder = $builder;
        $this->chunk = $chunk;
    }

    /**
     * @return \Generator
     */
    public function getIterator()
    {
        /** @var Builder|\Illuminate\Database\Query\Builder|Model $query */
        $query = clone $this->builder;

        $skip  = 0;
        $count = $query->count();
        do {
            $items = $query->skip($skip)->take($this->chunk)->get();
            foreach ($items as $item) {
                yield $item;
            }
            $skip += $this->chunk;
        } while ($skip < $count + $this->chunk);
    }
}
