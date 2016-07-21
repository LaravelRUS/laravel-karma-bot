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

class StandartSlackRoom extends AbstractRoom
{
    /**
     * AbstractRoom constructor.
     *
     * @param string $id
     * @param string|array $groups
     * @param array  $middleware
     */
    public function __construct($id, $groups = '*', array $middleware = [])
    {
        parent::__construct();

        $this->id = $id;
        $this->alias = $id;
        $this->groups = (array) $groups;

        $this->setMiddleware($middleware);
    }

    /**
     * @return string
     */
    public function driver()
    {
        return 'slack';
    }
}