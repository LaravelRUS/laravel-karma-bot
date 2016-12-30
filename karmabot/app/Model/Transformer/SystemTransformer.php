<?php declare(strict_types = 1);
/**
 * This file is part of Laravel-Karma package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace KarmaBot\Model\Transformer;

use Illuminate\Contracts\Container\Container;
use KarmaBot\Model\System;
use KarmaBot\Model\System\DriversMap;
use Psr\Log\LoggerInterface;
use Serafim\KarmaCore\Factory;
use Serafim\KarmaCore\Io\SystemInterface;
use Serafim\KarmaCore\Io\UserInterface;

/**
 * Class SystemTransformer
 * @package KarmaBot\Model\Transformer
 * @mixin System
 */
trait SystemTransformer
{
    /**
     * @param SystemInterface $system
     * @param string $driver
     * @param string $token
     * @return System|SystemTransformer|static
     */
    public static function new(SystemInterface $system, string $driver, string $token)
    {
        $model = new static();

        $model->title  = $system->getName();
        $model->driver = DriversMap::findAliasByDriver($driver);
        $model->token  = $token;

        return $model;
    }

    /**
     * @param Container $container
     * @return SystemInterface
     * @throws \InvalidArgumentException
     */
    public function getSystemConnection(Container $container): SystemInterface
    {
        $factory = $container->make(Factory::class);
        $factory->setLogger($container->make(LoggerInterface::class));

        return $factory->create($this->driver_class, ['token' => $this->token]);
    }
}
