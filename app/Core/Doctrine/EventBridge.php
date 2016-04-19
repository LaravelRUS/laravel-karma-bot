<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 19.04.2016 2:06
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Core\Doctrine;

use Doctrine\Common\EventArgs;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Event\OnClassMetadataNotFoundEventArgs;
use Doctrine\ORM\Event\OnClearEventArgs;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Doctrine\ORM\Event\PreFlushEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Illuminate\Events\Dispatcher;

/**
 * Class EventBridge
 * @package Core\Doctrine
 */
class EventBridge implements Events
{
    /**
     * @var Dispatcher
     */
    private $dispatcher;

    /**
     * EventBridge constructor.
     */
    public function __construct()
    {
        $this->dispatcher = new Dispatcher();
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function preRemove(LifecycleEventArgs $args)
    {
        $this->dispatcher->fire('preRemove', ['event' => $args]);
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postRemove(LifecycleEventArgs $args)
    {
        $this->dispatcher->fire('postRemove', ['event' => $args]);
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $this->dispatcher->fire('prePersist', ['event' => $args]);
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postPersist(LifecycleEventArgs $args)
    {
        $this->dispatcher->fire('postPersist', ['event' => $args]);
    }

    /**
     * @param PreUpdateEventArgs $args
     */
    public function preUpdate(PreUpdateEventArgs $args)
    {
        $this->dispatcher->fire('preUpdate', ['event' => $args]);
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postUpdate(LifecycleEventArgs $args)
    {
        $this->dispatcher->fire('postUpdate', ['event' => $args]);
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postLoad(LifecycleEventArgs $args)
    {
        $this->dispatcher->fire('postLoad', ['event' => $args]);
    }

    /**
     * @param LoadClassMetadataEventArgs $args
     */
    public function loadClassMetadata(LoadClassMetadataEventArgs $args)
    {
        $this->dispatcher->fire('loadClassMetadata', ['event' => $args]);
    }

    /**
     * @param OnClassMetadataNotFoundEventArgs $args
     */
    public function onClassMetadataNotFound(OnClassMetadataNotFoundEventArgs $args)
    {
        $this->dispatcher->fire('onClassMetadataNotFound', ['event' => $args]);
    }


    /**
     * @param PreFlushEventArgs $args
     */
    public function preFlush(PreFlushEventArgs $args)
    {
        $this->dispatcher->fire('preFlush', ['event' => $args]);
    }

    /**
     * @param OnFlushEventArgs $args
     */
    public function onFlush(OnFlushEventArgs $args)
    {
        $this->dispatcher->fire('onFlush', ['event' => $args]);
    }

    /**
     * @param PostFlushEventArgs $args
     */
    public function postFlush(PostFlushEventArgs $args)
    {
        $this->dispatcher->fire('postFlush', ['event' => $args]);
    }

    /**
     * @param OnClearEventArgs $args
     */
    public function onClear(OnClearEventArgs $args)
    {
        $this->dispatcher->fire('onClear', ['event' => $args]);
    }

    /**
     * @param string $event
     * @param \Closure $callback
     * @return Events
     */
    public function subscribe(string $event, \Closure $callback) : Events
    {
        $this->dispatcher->listen($event, $callback);
        return $this;
    }

    public function once(string $event, \Closure $callback) : Events
    {

    }
}