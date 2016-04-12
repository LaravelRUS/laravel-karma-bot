<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 12.04.2016 18:42
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Core\Entity;

/**
 * Class Builder
 * @package Core\Entity
 */
class Builder
{
    /**
     * @param $instance
     * @param string $field
     * @param $value
     */
    public static function fill($instance, string $field, $value)
    {
        \EntityManager::getClassMetadata(get_class($instance))
            ->setFieldValue($instance, $field, $value);
    }
}
