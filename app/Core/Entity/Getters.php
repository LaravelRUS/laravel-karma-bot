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
 * Class Getters
 * @package Core\Entity
 */
trait Getters
{
    /**
     * @param string $key
     * @return mixed
     * @throws \LogicException
     */
    public function __get($key)
    {
        if (!method_exists($this, $this->getGetterMethod($key))) {
            throw new \LogicException(sprintf('Property %s::%s not accessible', static::class, $key));
        }

        if (!property_exists($this, $key)) {
            return $this->{$this->getGetterMethod($key)}();
        }

        return $this->{$this->getGetterMethod($key)}($this->$key);
    }

    /**
     * @param string $key
     * @return string
     */
    private function getGetterMethod($key)
    {
        return 'get' . Str::studly($key);
    }
}
