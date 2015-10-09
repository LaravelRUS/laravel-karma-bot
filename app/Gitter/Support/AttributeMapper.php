<?php

/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 24.09.2015 16:04
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Gitter\Support;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Arr;

/**
 * Class AttributeMapper
 * @package App\Gitter\Support
 */
class AttributeMapper implements Arrayable
{
    /**
     * @var array
     */
    protected $attributes = [];

    /**
     * @param array $attributes
     */
    public function __construct(array $attributes)
    {
        $this->attributes = $attributes;
    }

    /**
     * @param $key
     * @return AttributeMapper
     */
    public function delete($key): AttributeMapper
    {
        if ($this->has($key)) {
            unset($this->attributes[$key]);
        }
        return $this;
    }

    /**
     * @param $from
     * @param $to
     * @return AttributeMapper
     * @throws \InvalidArgumentException
     */
    public function copy($from, $to): AttributeMapper
    {
        if ($this->has($from)) {
            if ($this->has($to)) {
                $message = sprintf('Can not rename %s. %s already exists.', $from, $to);
                throw new \InvalidArgumentException($message);
            }

            $this->attributes[$to] = $this->attributes[$from];
        }

        return $this;
    }

    /**
     * @param $key
     * @param callable $callback
     * @param null|string $newName
     * @return AttributeMapper
     * @throws \InvalidArgumentException
     */
    public function value($key, callable $callback, $newName = null): AttributeMapper
    {
        if ($this->has($key)) {
            $this->attributes[$key] = $callback($this->attributes[$key]);
            if ($newName !== null) {
                $this->rename($key, $newName);
            }
        }
        return $this;
    }

    /**
     * @param $key
     * @param $value
     * @return AttributeMapper
     */
    public function set($key, $value): AttributeMapper
    {
        $this->attributes[$key] = $value;
        return $this;
    }

    /**
     * @param $from
     * @param $to
     * @return AttributeMapper
     * @throws \InvalidArgumentException
     */
    public function rename($from, $to): AttributeMapper
    {
        $this->copy($from, $to);
        $this->delete($from);

        return $this;
    }

    /**
     * @param $key
     * @return mixed|bool
     */
    public function has($key): bool
    {
        return array_key_exists($key, $this->attributes);
    }

    /**
     * @param array $values
     * @return AttributeMapper
     */
    public function only(array $values): AttributeMapper
    {
        $this->attributes = Arr::only($this->attributes, $values);
        return $this;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->attributes;
    }

    /**
     * @return object
     */
    public function toObject()
    {
        return (object)$this->toArray();
    }
}
