<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 11.10.2015 8:26
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App\Gitter\Subscriber;

use Illuminate\Contracts\Container\Container;

/**
 * Class Storage
 * @package App\Gitter\Subscriber
 */
class Storage
{
    /**
     * @var SubscriberInterface[]
     */
    protected $subscribers = [];

    /**
     * @var Container
     */
    protected $container;

    /**
     * Storage constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param $class
     * @return $this
     */
    public function add($class)
    {
        $this->container->bind($class, $class);
        $subscriber = $this->container->make($class);

        $this->subscribers[] = $subscriber;

        $subscriber->handle();

        return $this;
    }
}