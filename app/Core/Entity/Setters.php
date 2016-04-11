<?php
/**
 * This file is part of Api package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 24.03.2016 15:12
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Core\Entity;

use Illuminate\Support\Str;

/**
 * Class Setters
 * @package Core\Entity
 */
trait Setters
{
    /**
     * @param string $key
     * @param $value
     * @return mixed
     * @throws \LogicException
     */
    public function __set($key, $value)
    {
        if (!method_exists($this, $this->getSetterMethod($key))) {
            throw new \LogicException(sprintf('Property %s::%s not writable', static::class, $key));
        }

        if (!property_exists($this, $key)) {
            return $this->{$this->getSetterMethod($key)}($value);
        }

        return $this->{$this->getSetterMethod($key)}($value);
    }

    /**
     * @param string $key
     * @return string
     */
    private function getSetterMethod($key)
    {
        return 'set' . Str::studly($key);
    }
}
