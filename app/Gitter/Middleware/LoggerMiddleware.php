<?php

/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 24.09.2015 15:47
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Gitter\Middleware;

use App\Message;
use Illuminate\Contracts\Config\Repository;

/**
 * Class LoggerMiddleware
 * @package App\Gitter\Middleware
 */
class LoggerMiddleware implements MiddlewareInterface
{
    /**
     * @var Repository
     */
    protected $config;

    /**
     * @param Repository $config
     */
    public function __construct(Repository $config)
    {
        $this->config = $config;
    }

    /**
     * @param Message $message
     * @return mixed
     */
    public function handle(Message $message)
    {

        $text = $message->user->name . ': ' . $message->text;

        #$this->log->write($message);


        return $message;
    }
}
