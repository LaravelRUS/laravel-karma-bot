<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 12.04.2016 18:28
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
     * @var array|string[]
     */
    private $readOnlyDeclarations = [];

    /**
     * @param string $property
     * @return bool
     */
    private function hasReadableDeclaration(string $property) : bool
    {
        if ($this->readOnlyDeclarations === []) {
            $context = new \ReflectionClass($this);

            while ($context) {
                $this->readOnlyDeclarations = array_merge(
                    $this->readOnlyDeclarations,
                    $this->getDeclarations($context)
                );

                $context = $context->getParentClass();
            }
        }


        return in_array($property, $this->readOnlyDeclarations, true);
    }

    /**
     * @param $class
     * @return array
     */
    private function getDeclarations(\ReflectionClass $class) : array
    {
        $doc = $class->getDocComment();

        $pattern = '/@property\-read\s(?:.*?)\$([a-z_]+[0-9a-z_\x7f-\xff]*)/isu';

        preg_match_all($pattern, $doc, $matches);

        return $matches[1];
    }

    /**
     * @param string $property
     * @return string
     */
    private function getGetter(string $property) : string
    {
        return 'get' . Str::camel($property);
    }

    /**
     * @param string $property
     * @return bool
     */
    private function hasGetter(string $property) : bool
    {
        $getter = $this->getGetter($property);
        return method_exists($this, $getter);
    }

    /**
     * @param string $name
     * @return mixed|void
     */
    public function __get($name)
    {
        if ($this->hasGetter($name)) {
            $getter = $this->getGetter($name);
            return $this->$getter();
        }

        if ($this->hasReadableDeclaration($name)) {
            return $this->getPropertyValue($name);
        }
    }


    /**
     * @param string $name
     * @return mixed
     */
    private function getPropertyValue(string $name)
    {
        $reflection = new \ReflectionProperty($this, $name);
        $reflection->setAccessible(true);

        return $reflection->getValue($this);
    }
}
