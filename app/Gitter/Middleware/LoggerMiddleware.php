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

use App\Gitter\Logger;
use App\Gitter\Models\MessageObject;
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
     * @param $data
     * @return mixed
     */
    public function handle($data)
    {
        $message = $data;
        if ($data instanceof MessageObject) {
            $message = $data->user->name . ': ' . $data->text;
        }
        #$this->log->write($message);


        return $data;
    }
}
