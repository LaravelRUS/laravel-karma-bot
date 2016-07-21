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

namespace Domains\Room;

use Domains\Bot\ClientInterface;
use Domains\Middleware\Storage;

interface RoomInterface
{
    /**
     * @return string
     */
    public function id();

    /**
     * @return string
     */
    public function alias();

    /**
     * @return string
     */
    public function groups();

    /**
     * @return string
     */
    public function driver();

    /**
     * @return ClientInterface
     */
    public function client();

    /**
     * @return Storage
     */
    public function middleware();

    /**
     * @return void
     */
    public function listen();

    /**
     * @param string $message
     */
    public function sendMessage($message);
}