<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 19.04.2016 2:11
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Core\Doctrine;

use Doctrine\ORM\Events as BaseEvents;

/**
 * Interface Events
 * @package Core\Doctrine
 */
interface Events
{
    const PRE_REMOVE          = BaseEvents::preRemove;
    const POST_REMOVE         = BaseEvents::postRemove;
    const PRE_PERSIST         = BaseEvents::prePersist;
    const POST_PERSIST        = BaseEvents::postPersist;
    const PRE_UPDATE          = BaseEvents::preUpdate;
    const POST_UPDATE         = BaseEvents::postUpdate;
    const POST_LOAD           = BaseEvents::postLoad;
    const LOAD_METADATA       = BaseEvents::loadClassMetadata;
    const LOAD_METADATA_ERROR = BaseEvents::onClassMetadataNotFound;
    const PRE_FLUSH           = BaseEvents::preFlush;
    const ON_FLUSH            = BaseEvents::onFlush;
    const POST_FLUSH          = BaseEvents::postFlush;
    const ON_CLEAR            = BaseEvents::onClear;

    /**
     * @param string $event
     * @param \Closure $callback
     * @return Events
     */
    public function subscribe(string $event, \Closure $callback) : Events;
}