<?php declare(strict_types=1);
/**
 * This file is part of Laravel-Karma package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace KarmaBot\Bot\Middleware;

use Illuminate\Support\Str;

/**
 * Class AbstractMiddleware
 * @package KarmaBot\Bot\Middleware
 */
abstract class AbstractMiddleware implements MiddlewareInterface
{
    /**
     * @var string
     */
    protected $group = PredefineGroups::GROUP_DEFAULT;

    /**
     * @var int
     */
    protected $flow = self::FLOW_LOGIC_SKIP_SELF;

    /**
     * @var null|string
     */
    protected static $name;

    /**
     * @return string
     */
    public function getGroup(): string
    {
        return $this->group;
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return static::$name ?? Str::snake(static::getClassName());
    }

    /**
     * @return string
     */
    private static function getClassName(): string
    {
        return (new \ReflectionClass(static::class))->getShortName();
    }

    /**
     * @return int
     */
    public function getFlowType(): int
    {
        return $this->flow;
    }
}
