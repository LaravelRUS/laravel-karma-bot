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

namespace Interfaces\Gitter\Room;

use Interfaces\Gitter\Middleware\Storage;

class StandartGitterRoom extends AbstractRoom
{
    /**
     * @var \Interfaces\Gitter\Client
     */
    protected $client;

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
        $this->client = app('bot.manager')->driver($this->driver());

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
        return 'gitter';
    }

    /**
     * @return \Interfaces\Gitter\Client
     */
    public function client()
    {
        return $this->client;
    }

    /**
     * @return Storage
     */
    public function middleware()
    {
        return $this->middleware;
    }
}