<?php declare(strict_types = 1);
/**
 * This file is part of Laravel-Karma package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace KarmaBot\Model\System;

use Serafim\KarmaCore\System\Gitter\GitterSystem;
use Serafim\KarmaCore\System\Slack\SlackSystem;

/**
 * Class DriversMap
 * @package KarmaBot\Model\System
 */
final class DriversMap
{
    /**
     * @var array
     */
    protected static $driversMapping = [
        'gitter' => GitterSystem::class,
        'slack'  => SlackSystem::class,
    ];

    /**
     * @return array
     */
    public static function getAvailableDrivers(): array
    {
        return array_values(static::$driversMapping);
    }

    /**
     * @return array
     */
    public static function getAvailableAliases(): array
    {
        return array_keys(static::$driversMapping);
    }

    /**
     * @param string $driver
     * @return string
     * @throws \InvalidArgumentException
     */
    public static function getAliasByDriver(string $driver): string
    {
        if (($alias = static::findAliasByDriver($driver)) !== null) {
            return $alias;
        }

        throw new \InvalidArgumentException(sprintf('Invalid driver <%s> name', $driver));
    }

    /**
     * @param string $driver
     * @return null|string
     */
    public static function findAliasByDriver(string $driver): ?string
    {
        return array_flip(static::$driversMapping)[$driver] ?? null;
    }

    /**
     * @param string $alias
     * @return string
     * @throws \InvalidArgumentException
     */
    public static function getDriverByAlias(string $alias): string
    {
        if (null !== ($driver = static::findDriverByAlias($alias))) {
            return $driver;
        }

        throw new \InvalidArgumentException(sprintf('Invalid alias <%s> name', $alias));
    }

    /**
     * @param string $alias
     * @return null|string
     */
    public static function findDriverByAlias(string $alias): ?string
    {
        return static::$driversMapping[$alias] ?? null;
    }
}
