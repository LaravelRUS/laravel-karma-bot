<?php
/**
 * This file is part of GitterBot package.
 *
 * @author butschster <butschster@gmail.com>
 * @date   20.07.2016 15:27
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Interfaces\Gitter;

use Domains\Room\AbstractRoom;

class StandartGitterRoom extends AbstractRoom
{
    /**
     * AbstractRoom constructor.
     *
     * @param string $alias
     * @param string|array $groups
     * @param array  $middleware
     */
    public function __construct($alias, $groups = '*', array $middleware = [])
    {
        parent::__construct();

        $this->alias = $alias;
        $this->groups = (array) $groups;

        $this->setMiddleware($middleware);
    }

    public function listen()
    {
        $result = $this->client()->getGitterClient()->http->getRoomByUri($this->alias())->wait();
        $this->id = $result->id;

        parent::listen();
    }

    /**
     * @return string
     */
    public function id()
    {
        if (empty($this->id)) {
            return $this->alias();
        }

        return parent::id();
    }

    /**
     * @return string
     */
    public function driver()
    {
        return 'gitter';
    }
}