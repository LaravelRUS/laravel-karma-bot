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
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\ORMInvalidArgumentException;

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
        static::getEntityManager()
            ->getClassMetadata(get_class($instance))
            ->setFieldValue($instance, $field, $value);
    }

    /**
     * @param $entity
     * @param string $identity
     * @return mixed
     */
    public static function synchronized($entity, $identity = 'id')
    {
        $em = static::getEntityManager();

        $repository = $em->getRepository(get_class($entity));
        $exists     = $repository->find($entity->$identity);

        return $exists ?: $entity;
    }

    /**
     * @return EntityManager
     */
    private static function getEntityManager()
    {
        return app(EntityManager::class);
    }
}
