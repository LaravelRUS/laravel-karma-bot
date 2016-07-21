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

namespace Interfaces\Slack;

use Domains\Room\AbstractRoom;
use Domains\Middleware\Storage;

class StandartSlackRoom extends AbstractRoom
{

    /**
     * AbstractRoom constructor.
     *
     * @param string $id
     * @param string $alias
     * @param string|array $groups
     * @param array  $middleware
     */
    public function __construct($id, $alias, $groups = '*', array $middleware = [])
    {
        parent::__construct();

        $this->id = $id;
        $this->alias = $alias;
        $this->groups = (array) $groups;

        $this->setMiddleware($middleware);
    }

    /**
     * @return string
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function alias()
    {
        return $this->alias;
    }

    /**
     * @return array
     */
    public function groups()
    {
        return $this->groups;
    }

    /**
     * @return string
     */
    public function driver()
    {
        return 'slack';
    }
}