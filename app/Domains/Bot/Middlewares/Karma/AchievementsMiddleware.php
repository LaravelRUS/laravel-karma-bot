<?php
/**
 * This file is part of GitterBot package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date 19.04.2016 0:06
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Domains\Bot\Middlewares\Karma;

use Core\Doctrine\Events;
use Doctrine\Common\Annotations\Reader;
use Domains\Achieve\Achievements;
use Domains\Achieve\Repository;
use Domains\Bot\Middlewares\Middleware;
use Illuminate\Container\Container;

/**
 * Class AchievementsMiddleware
 * @package Domains\Bot\Middlewares\Karma
 */
class AchievementsMiddleware implements Middleware
{
    /**
     * @var Repository
     */
    private $repository;

    /**
     * AchievementsMiddleware constructor.
     * @param Container $app
     * @param Events $events
     * @param Reader $reader
     */
    public function __construct(Container $app, Events $events, Reader $reader)
    {
        $this->repository = new Repository($app, $events, $reader);
    }
}