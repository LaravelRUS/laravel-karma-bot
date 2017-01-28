<?php declare(strict_types=1);
/**
 * This file is part of Laravel-Karma package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Model\System;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Concerns\HasAttributes;
use Illuminate\Database\Eloquent\Concerns\HidesAttributes;

/**
 * Class Driver
 * @package App\Model\System
 *
 * @property-read string $alias
 * @property-read string $driver
 */
class Driver implements Arrayable
{
    use HasAttributes;
    use HidesAttributes;

    /**
     * Driver constructor.
     * @param string $alias
     * @param string $driver
     */
    public function __construct(string $alias, string $driver)
    {
        $this->attributes = ['alias' => $alias, 'driver' => $driver];
    }

    /**
     * @return bool
     */
    public function getIncrementing()
    {
        return false;
    }

    /**
     * @return array
     */
    public function getVisible(): array
    {
        return [];
    }

    /**
     * @return array
     */
    public function getHidden(): array
    {
        return [];
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->getArrayableAttributes();
    }
}